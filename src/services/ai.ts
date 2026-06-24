import { Book } from "@/types/book";

interface GeneratedSummary {
  brief: string;
  themes: string[];
  whyRead: string;
  isSpringerAI?: boolean;
}

export interface Education5Pillar {
  title: string;
  content: string;
  icon: string;
  actionable_step: string;
}

export interface StudyGuide {
  bookId: string;
  pillars: {
    teaching: Education5Pillar;
    research: Education5Pillar;
    community_service: Education5Pillar;
    innovation: Education5Pillar;
    industrialization: Education5Pillar;
  };
  heritage_context: string;
  curriculum_alignment: string;
  critical_inquiry: string[];
  practical_application: string;
  vision_2030_alignment: string;
  last_updated: string;
}

/**
 * Extracts key themes and concepts from book content or description
 */
const performInDepthAnalysis = (book: Book) => {
  const content = (book.content || book.description || "").toLowerCase();
  const title = book.title.toLowerCase();
  
  const keywords = {
    heritage: ["culture", "history", "tradition", "indigenous", "ancestry", "heritage", "great zimbabwe", "archaeology"],
    technology: ["digital", "software", "computer", "engineering", "machine", "innovation", "data", "internet", "tech"],
    agriculture: ["farm", ["crop", "soil", "water", "irrigation", "climate", "livestock", "food", "nutrition"]],
    economy: ["money", "business", "market", "trade", "industry", "manufacturing", "growth", "finance"],
    health: ["medicine", "doctor", "disease", "health", "biology", "care", "wellness"],
    mining: ["mineral", "mining", "lithium", "gold", "extraction", "geology", "earth", "rock"]
  };

  const detected = Object.entries(keywords)
    .filter(([_, list]) => {
      if (Array.isArray(list)) {
        return list.some(word => {
            if (Array.isArray(word)) return word.some(w => content.includes(w) || title.includes(w));
            return content.includes(word as string) || title.includes(word as string);
        });
      }
      return content.includes(list) || title.includes(list);
    })
    .map(([key]) => key);

  return detected.length > 0 ? detected : ["general academic"];
};

/**
 * Maps analysis results to Education 5.0 concepts specific to Zimbabwe
 */
const mapToEducation5Principles = (analysis: string[], book: Book) => {
  const contextMap: Record<string, string> = {
    heritage: "Leveraging Zimbabwe's rich cultural history and Great Zimbabwe architectural logic to inform modern structural and social design.",
    technology: "Bridging the digital divide by applying global technological frameworks to local Zimbabwean infrastructure bottlenecks.",
    agriculture: "Utilizing climate-smart agriculture and indigenous small grains (Zviyo/Mapfunde) to ensure national food security under Vision 2030.",
    economy: "Transitioning from a primary resource exporter to a value-added manufacturing hub through heritage-based industrialization.",
    health: "Integrating traditional pharmacological knowledge with modern medical research to create a robust national health ecosystem.",
    mining: "Moving beyond extraction to lithium and platinum value-addition within Zimbabwe to drive the 4th Industrial Revolution.",
    "general academic": "Contextualizing global knowledge within the 'Kuziva, Kugona, neKuita' (Knowing, Being, and Doing) framework of Education 5.0."
  };

  return contextMap[analysis[0]] || contextMap["general academic"];
};

/**
 * Generates an Education 5.0 Study Guide for a resource using in-depth analysis.
 */
export const generateEducation5StudyGuide = async (book: Book): Promise<StudyGuide> => {
  // Simulate heavy AI processing for analysis
  await new Promise(resolve => setTimeout(resolve, 3500));

  const analysis = performInDepthAnalysis(book);
  const heritageContext = mapToEducation5Principles(analysis, book);
  const mainTheme = analysis[0];

  // Specific content generation based on analysis
  const getPillarContent = (pillar: string) => {
    switch (pillar) {
      case 'teaching':
        return {
          content: `Adapt ${book.title}'s core findings into a curriculum that emphasizes outcome-based education. For ${mainTheme}-focused studies, use the 'Heritage-Based' lens to teach students how these global theories manifest in the Zimbabwean landscape.`,
          actionable_step: "Create a tutorial series that challenges students to find 3 local parallels to the theories presented in the text."
        };
      case 'research':
        return {
          content: `Conduct a gap analysis between ${book.author}'s conclusions and the current state of ${mainTheme} in Zimbabwe. Focus on how 'Indigenous Knowledge Systems' (IKS) can fill these research voids.`,
          actionable_step: "Draft a research proposal for a case study centered on a Zimbabwean province (e.g., Manicaland or Midlands) using this framework."
        };
      case 'community_service':
        return {
          content: `How can the ${mainTheme} principles in this work be simplified for community outreach? Focus on empowering rural cooperatives and SMEs using these insights to solve immediate local needs.`,
          actionable_step: "Design a 1-day community workshop layout that translates one complex chapter into a practical local solution."
        };
      case 'innovation':
        return {
          content: `Innovate by blending ${book.title}'s methodology with Zimbabwean resources. If the text discusses ${mainTheme}, explore how we can 'leapfrog' traditional stages of development using this knowledge.`,
          actionable_step: "Identify a specific Zimbabwean industrial bottleneck that these insights could solve through a new prototype."
        };
      case 'industrialization':
        return {
          content: `The end-goal is the 'Production of Goods and Services'. Analyze how the theories here can be commercialized to create new value chains in Zimbabwe, moving away from raw material exports.`,
          actionable_step: "Develop a 'Heritage-Based' business model for a start-up that applies this research to a local manufacturing process."
        };
      default:
        return { content: "", actionable_step: "" };
    }
  };

  const curriculumAlignment = `Aligned with Zimbabwe NQF Level ${book.category === 'Research' ? '8' : '7'} - ${mainTheme.toUpperCase()} DEVELOPMENT`;

  return {
    bookId: book.id,
    heritage_context: heritageContext,
    curriculum_alignment: curriculumAlignment,
    pillars: {
      teaching: {
        title: "Teaching",
        ...getPillarContent('teaching'),
        icon: "GraduationCap"
      },
      research: {
        title: "Research",
        ...getPillarContent('research'),
        icon: "Search"
      },
      community_service: {
        title: "Community Service",
        ...getPillarContent('community_service'),
        icon: "Users"
      },
      innovation: {
        title: "Innovation",
        ...getPillarContent('innovation'),
        icon: "Lightbulb"
      },
      industrialization: {
        title: "Industrialization",
        ...getPillarContent('industrialization'),
        icon: "Factory"
      }
    },
    critical_inquiry: [
      `How does ${book.author}'s perspective on ${mainTheme} challenge traditional Western-centric academic frameworks in Zimbabwe?`,
      `In what ways can the methodologies in "${book.title}" be localized using Zimbabwean indigenous knowledge?`,
      `What are the ethical implications of implementing ${mainTheme} innovations in rural Zimbabwean communities?`
    ],
    practical_application: `Scholars can apply the ${mainTheme} frameworks found in this text to develop localized prototypes that address the specific socio-economic challenges outlined in the NDS1 (National Development Strategy 1).`,
    vision_2030_alignment: `This resource directly contributes to the "Human Capital Development" and "Innovation and Technological Development" clusters of Zimbabwe's Vision 2030 by providing foundational knowledge in ${mainTheme}.`,
    last_updated: new Date().toISOString()
  };
};

/**
 * Simulates an AI-generated summary for a book using its metadata.
 */
export const generateBookSummary = async (book: Book): Promise<GeneratedSummary> => {
  await new Promise(resolve => setTimeout(resolve, 1500));

  const analysis = performInDepthAnalysis(book);
  const isSpringer = book.source === 'springer' || book.source === 'crossref';

  const themes = isSpringer 
    ? ["Empirical Analysis", "Systemic Review", "Methodological Rigor"]
    : analysis.map(t => t.charAt(0).toUpperCase() + t.slice(1)).slice(0, 3);

  let brief = `Through an in-depth AI analysis of "${book.title}", we have identified core themes relating to ${themes.join(", ")}. This resource ${book.description.length > 50 ? 'offers a sophisticated look at ' + book.description.substring(0, 100) + '...' : 'provides a foundational understanding of the subject matter'}.`;
  
  if (isSpringer) {
    brief = `[Springer Nature AI Analysis]: "${book.title}" provides a critical examination of ${themes[0]} within ${book.category}. The analysis highlights the work of ${book.author} as a primary source for Education 5.0 research.`;
  }

  const whyRead = `This resource is vital for Zimbabwean scholars because it directly addresses the intersection of global ${analysis[0]} trends and local developmental needs, fulfilling the 'Industrialization' pillar of the national curriculum.`;

  return {
    brief,
    themes,
    whyRead,
    isSpringerAI: isSpringer
  };
};
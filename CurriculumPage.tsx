import React from "react";
import { GraduationCap, Landmark, Sparkles } from "lucide-react";

export function CurriculumPage() {
  return (
    <div className="container mx-auto px-4 py-12 space-y-12">
      <div className="flex flex-col gap-2">
        <div className="flex items-center gap-3">
          <div className="p-3 rounded-2xl bg-[#0c1c38] text-[#d4af37]">
            <GraduationCap className="w-8 h-8" />
          </div>
          <h1 className="text-4xl font-black tracking-tighter text-[#0c1c38] uppercase italic">Curriculum Hub</h1>
        </div>
        <p className="text-slate-500 font-medium text-lg max-w-2xl">
          National Education 5.0 alignment framework. Mapping institutional research to national heritage-based outcomes.
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {[
          { title: "Teaching", color: "bg-blue-500", icon: GraduationCap },
          { title: "Research", color: "bg-purple-500", icon: Sparkles },
          { title: "Community", color: "bg-emerald-500", icon: Landmark },
          { title: "Innovation", color: "bg-amber-500", icon: Sparkles },
          { title: "Industrialization", color: "bg-rose-500", icon: Landmark },
        ].map((pillar) => (
          <div key={pillar.title} className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group overflow-hidden relative">
            <div className={`absolute top-0 right-0 w-32 h-32 ${pillar.color} opacity-[0.03] group-hover:opacity-[0.08] rounded-full -mr-16 -mt-16 transition-all`} />
            <div className={`w-14 h-14 rounded-2xl ${pillar.color}/10 flex items-center justify-center mb-6`}>
              <pillar.icon className={`w-7 h-7 ${pillar.color.replace('bg-', 'text-')}`} />
            </div>
            <h3 className="text-xl font-black text-[#0c1c38] uppercase tracking-wider mb-3">{pillar.title} Pillar</h3>
            <p className="text-slate-500 text-sm font-medium leading-relaxed">
              Explore resources specifically indexed to support the {pillar.title.toLowerCase()} requirements of the Zimbabwean National Curriculum.
            </p>
          </div>
        ))}
      </div>
    </div>
  );
}
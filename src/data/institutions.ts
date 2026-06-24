export interface Institution {
  id: string;
  name: string;
  country?: string;
  category?: string;
}

export const ZIMBABWE_INSTITUTIONS: Institution[] = [
  { id: '1', name: 'University of Zimbabwe', country: 'Zimbabwe', category: 'university' },
];

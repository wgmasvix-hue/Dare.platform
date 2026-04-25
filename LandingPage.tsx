import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Search, Landmark, GraduationCap, School, BookOpen, ChevronRight, Sparkles, ShieldCheck, Library } from 'lucide-react';
import { Institution, ZIMBABWE_INSTITUTIONS } from '../../data/institutions';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Logo } from '../layout/Logo';
import { Card } from '@/components/ui/card';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';

interface LandingPageProps {
  onSelectInstitution: (institution: Institution) => void;
}

export const LandingPage: React.FC<LandingPageProps> = ({ onSelectInstitution }) => {
  const [searchQuery, setSearchQuery] = useState('');
  const [activeCategory, setActiveCategory] = useState<string>('all');

  const filteredInstitutions = ZIMBABWE_INSTITUTIONS.filter(inst => {
    const matchesSearch = inst.name.toLowerCase().includes(searchQuery.toLowerCase());
    const matchesCategory = activeCategory === 'all' || inst.category === activeCategory;
    return matchesSearch && matchesCategory;
  });

  const categories = [
    { id: 'all', name: 'All institutions', icon: <Library className="w-4 h-4" /> },
    { id: 'University', name: 'Universities', icon: <GraduationCap className="w-4 h-4" /> },
    { id: 'Polytechnic', name: 'Polytechnics', icon: <Landmark className="w-4 h-4" /> },
    { id: 'Teachers College', name: 'Teachers Colleges', icon: <School className="w-4 h-4" /> },
    { id: 'Vocational/Technical', name: 'Vocational', icon: <BookOpen className="w-4 h-4" /> },
  ];

  return (
    <div className="min-h-screen bg-[#0c1c38] text-white relative overflow-hidden selection:bg-[#d4af37]/20">
      {/* Background with Generated Image */}
      <div className="absolute inset-0 z-0">
        <img 
          src="https://storage.googleapis.com/dala-prod-public-storage/generated-images/ac009de3-7976-4350-ae23-5fdb18d3f125/institutional-landing-background-fbab9460-1776845961887.webp" 
          alt="Library Background" 
          className="w-full h-full object-cover opacity-30 grayscale"
        />
        <div className="absolute inset-0 bg-gradient-to-b from-[#0c1c38] via-transparent to-[#0c1c38]" />
      </div>

      <main className="relative z-10 container mx-auto px-4 py-20 flex flex-col items-center text-center">
        <motion.div
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="flex flex-col items-center gap-6 mb-12"
        >
          <div className="bg-[#d4af37] p-4 rounded-[2rem] shadow-2xl shadow-[#d4af37]/20">
            <Logo variant="dark" className="w-16 h-16" />
          </div>
          <div className="space-y-2">
            <h1 className="text-5xl md:text-7xl font-black tracking-tighter italic">
              DARE <span className="text-[#d4af37] not-italic">DIGITAL</span> LIBRARY
            </h1>
            <p className="text-[#d4af37] font-bold tracking-[0.4em] uppercase text-sm">
              Zimbabwe's Unified Knowledge Infrastructure
            </p>
          </div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, scale: 0.95 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.8, delay: 0.2 }}
          className="max-w-4xl w-full bg-white/5 backdrop-blur-3xl p-8 md:p-12 rounded-[3rem] border border-white/10 shadow-2xl space-y-12"
        >
          <div className="space-y-4">
            <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#d4af37]/20 text-[#d4af37] border border-[#d4af37]/30 text-[10px] font-black uppercase tracking-widest">
              <ShieldCheck className="w-3.5 h-3.5" />
              Secure Institutional Access
            </div>
            <h2 className="text-3xl md:text-4xl font-bold tracking-tight">Select Your Institution</h2>
            <p className="text-slate-400 max-w-xl mx-auto font-medium">
              Join the unified national research ecosystem. Access Heritage-Based Education 5.0 resources tailored for your campus.
            </p>
          </div>

          <div className="space-y-8">
            <div className="flex flex-col md:flex-row gap-4">
              <div className="relative flex-1 group">
                <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 group-focus-within:text-[#d4af37] transition-colors" />
                <Input 
                  placeholder="Search for your university or college..." 
                  className="pl-12 h-14 bg-white/5 border-white/10 text-white placeholder:text-slate-500 focus-visible:ring-[#d4af37] focus-visible:bg-white/10 rounded-2xl text-lg font-medium"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>
              
              <Tabs 
                value={activeCategory} 
                onValueChange={setActiveCategory} 
                className="w-full md:w-auto"
              >
                <TabsList className="bg-white/5 h-14 p-1.5 rounded-2xl border border-white/10 flex overflow-x-auto no-scrollbar">
                  {categories.map((cat) => (
                    <TabsTrigger 
                      key={cat.id} 
                      value={cat.id}
                      className="rounded-xl px-4 gap-2 data-[state=active]:bg-[#d4af37] data-[state=active]:text-[#0c1c38] font-bold shrink-0"
                    >
                      {cat.icon}
                      <span className="hidden sm:inline">{cat.name}</span>
                    </TabsTrigger>
                  ))}
                </TabsList>
              </Tabs>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
              <AnimatePresence mode="popLayout">
                {filteredInstitutions.map((inst, index) => (
                  <motion.div
                    key={inst.id}
                    layout
                    initial={{ opacity: 0, y: 10 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.3, delay: index * 0.02 }}
                  >
                    <button
                      onClick={() => onSelectInstitution(inst)}
                      className="w-full group text-left p-6 bg-white/5 border border-white/10 rounded-[2rem] hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-300 relative overflow-hidden"
                    >
                      <div className="relative z-10 flex flex-col gap-3">
                        <div className="flex items-center justify-between">
                          <div className="p-2.5 rounded-xl bg-white/10 group-hover:bg-[#0c1c38]/10 transition-colors">
                            {inst.category === 'University' ? <GraduationCap className="w-5 h-5 text-[#d4af37] group-hover:text-[#0c1c38]" /> : 
                             inst.category === 'Polytechnic' ? <Landmark className="w-5 h-5 text-[#d4af37] group-hover:text-[#0c1c38]" /> :
                             inst.category === 'Teachers College' ? <School className="w-5 h-5 text-[#d4af37] group-hover:text-[#0c1c38]" /> :
                             <BookOpen className="w-5 h-5 text-[#d4af37] group-hover:text-[#0c1c38]" />}
                          </div>
                          <ChevronRight className="w-4 h-4 text-slate-500 group-hover:text-[#0c1c38] translate-x-0 group-hover:translate-x-1 transition-all" />
                        </div>
                        <div className="space-y-1">
                          <h3 className="font-bold text-white group-hover:text-[#0c1c38] leading-tight transition-colors">{inst.name}</h3>
                          <p className="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-[#0c1c38]/60 transition-colors">
                            {inst.category}
                          </p>
                        </div>
                      </div>
                      
                      {/* Decorative Background Icon */}
                      <div className="absolute -bottom-4 -right-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <Sparkles className="w-24 h-24" />
                      </div>
                    </button>
                  </motion.div>
                ))}
              </AnimatePresence>

              {filteredInstitutions.length === 0 && (
                <div className="col-span-full py-20 flex flex-col items-center gap-4 text-slate-500">
                  <Search className="w-12 h-12 opacity-20" />
                  <p className="font-bold uppercase tracking-widest text-sm">No institutions found matching your search</p>
                </div>
              )}
            </div>
          </div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 1, delay: 1 }}
          className="mt-16 flex items-center gap-8 opacity-40 grayscale hover:grayscale-0 transition-all cursor-default"
        >
          <img src="https://storage.googleapis.com/dala-prod-public-storage/generated-images/ac009de3-7976-4350-ae23-5fdb18d3f125/education-5-0-symbol-52c356f0-1776845961602.webp" alt="Gov" className="h-12 w-auto" />
          <div className="h-10 w-px bg-white/20" />
          <p className="text-xs font-bold uppercase tracking-[0.2em]">National Academic Repository Node 01</p>
        </motion.div>
      </main>

      {/* Decorative patterns */}
      <div className="absolute -top-64 -left-64 w-[600px] h-[600px] bg-[#d4af37]/10 rounded-full blur-[120px] pointer-events-none" />
      <div className="absolute -bottom-64 -right-64 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[120px] pointer-events-none" />
    </div>
  );
};
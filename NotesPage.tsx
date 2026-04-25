import React from "react";
import { FileText, Sparkles, BookOpen } from "lucide-react";
import { Card } from "@/components/ui/card";

export function NotesPage() {
  return (
    <div className="container mx-auto px-4 py-12 space-y-12">
      <div className="flex flex-col gap-2">
        <div className="flex items-center gap-3">
          <div className="p-3 rounded-2xl bg-[#0c1c38] text-[#d4af37]">
            <FileText className="w-8 h-8" />
          </div>
          <h1 className="text-4xl font-black tracking-tighter text-[#0c1c38] uppercase italic">Research Notes</h1>
        </div>
        <p className="text-slate-500 font-medium text-lg max-w-2xl">
          Centralized repository for your personal insights and AI-generated study summaries.
        </p>
      </div>

      <div className="bg-white rounded-[3rem] border border-dashed border-slate-200 p-24 text-center">
        <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
          <FileText className="w-10 h-10" />
        </div>
        <h3 className="text-2xl font-black text-[#0c1c38] uppercase mb-4">No notes synced yet</h3>
        <p className="text-slate-400 max-w-xs mx-auto font-medium">
          Start reading and chatting with the AI Tutor to generate research notes that persist here.
        </p>
      </div>
    </div>
  );
}
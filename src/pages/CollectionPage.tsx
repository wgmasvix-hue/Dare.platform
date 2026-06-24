import React from "react";
import { Dashboard } from "../components/dashboard/Dashboard";
import { Book } from "../types/book";
import { Institution } from "../data/institutions";
import { motion } from "framer-motion";
import { Library, Landmark } from "lucide-react";

interface CollectionPageProps {
  searchQuery: string;
  onBookClick: (book: Book) => void;
  savedBooks: Book[];
  onToggleSave: (e: React.MouseEvent, book: Book) => void;
  currentInstitution: Institution | null;
}

export function CollectionPage({ savedBooks, onBookClick, onToggleSave, currentInstitution }: CollectionPageProps) {
  return (
    <div className="container mx-auto px-4 py-12 space-y-12">
      <div className="flex flex-col gap-2">
        <div className="flex items-center gap-3">
          <div className="p-3 rounded-2xl bg-[#d4af37]/10 text-[#d4af37]">
            <Library className="w-8 h-8" />
          </div>
          <h1 className="text-4xl font-black tracking-tighter text-[#0c1c38] uppercase italic">My Research Archive</h1>
        </div>
        <p className="text-slate-500 font-medium text-lg max-w-2xl">
          Your curated collection of academic resources, textbooks, and peer-reviewed journals.
        </p>
      </div>

      <Dashboard 
        searchQuery=""
        onBookClick={onBookClick}
        savedBooks={savedBooks}
        onToggleSave={onToggleSave}
        currentInstitution={currentInstitution}
      />
    </div>
  );
}
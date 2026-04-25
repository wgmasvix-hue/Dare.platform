import React from "react";
import { Dashboard } from "../components/dashboard/Dashboard";
import { Book } from "../types/book";
import { Institution } from "../data/institutions";

interface DashboardPageProps {
  searchQuery: string;
  onBookClick: (book: Book) => void;
  savedBooks: Book[];
  onToggleSave: (e: React.MouseEvent, book: Book) => void;
  currentInstitution: Institution | null;
}

export function DashboardPage(props: DashboardPageProps) {
  return <Dashboard {...props} />;
}
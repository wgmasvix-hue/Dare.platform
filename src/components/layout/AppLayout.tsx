import React from "react";
import { Institution } from "../../data/institutions";

interface AppLayoutProps {
  children: React.ReactNode;
  currentInstitution: Institution | null;
  savedCount: number;
  onLogout: () => void;
  searchQuery: string;
  onSearchChange: (query: string) => void;
  onAddClick: () => void;
  onSavedClick: () => void | Promise<void>;
}

export const AppLayout: React.FC<AppLayoutProps> = ({ children }) => {
  return <div>{children}</div>;
};

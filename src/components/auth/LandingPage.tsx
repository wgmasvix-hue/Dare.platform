import React from "react";
import { Institution } from "../../data/institutions";

interface LandingPageProps {
  onSelectInstitution: (inst: Institution) => void;
}

export const LandingPage: React.FC<LandingPageProps> = () => {
  return <div>LandingPage - Component not yet implemented</div>;
};

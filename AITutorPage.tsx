import React from "react";
import { AITutor } from "../components/library/AITutor";

export function AITutorPage() {
  return (
    <div className="h-[calc(100vh-64px)]">
      <AITutor book={null} />
    </div>
  );
}
import React from "react";
import { Book } from "../../types/book";

interface BookDetailsProps {
  book: Book | null;
  isOpen: boolean;
  onClose: () => void;
}

export const BookDetails: React.FC<BookDetailsProps> = () => {
  return <div>BookDetails - Component not yet implemented</div>;
};

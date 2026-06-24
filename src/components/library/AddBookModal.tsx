import React from "react";
import { Book } from "../../types/book";

interface AddBookModalProps {
  isOpen: boolean;
  onClose: () => void;
  onAdd: (book: Book) => void;
}

export const AddBookModal: React.FC<AddBookModalProps> = () => {
  return <div>AddBookModal - Component not yet implemented</div>;
};

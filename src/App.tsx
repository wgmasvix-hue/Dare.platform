import React, { useState, useEffect } from "react";
import { BookDetails } from "./components/library/BookDetails";
import { AddBookModal } from "./components/library/AddBookModal";
import { LandingPage } from "./components/auth/LandingPage";
import { AppLayout } from "./components/layout/AppLayout";
import { Book } from "./types/book";
import { Toaster } from "@/components/ui/sonner";
import { toast } from "sonner";
import { Institution } from "./data/institutions";
import { BrowserRouter, Routes, Route, Navigate, useNavigate } from "react-router-dom";

// Pages
import { DashboardPage } from "./pages/DashboardPage";
import { AITutorPage } from "./pages/AITutorPage";
import { CollectionPage } from "./pages/CollectionPage";
import { CurriculumPage } from "./pages/CurriculumPage";
import { NotesPage } from "./pages/NotesPage";

function AppContent() {
  const navigate = useNavigate();
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedBook, setSelectedBook] = useState<Book | null>(null);
  const [isDetailsOpen, setIsDetailsOpen] = useState(false);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [refreshTrigger, setRefreshTrigger] = useState(0);
  const [savedBooks, setSavedBooks] = useState<Book[]>([]);
  const [currentInstitution, setCurrentInstitution] = useState<Institution | null>(null);

  // Load saved books and institution from localStorage
  useEffect(() => {
    const saved = localStorage.getItem('dare_saved_resources');
    const savedInst = localStorage.getItem('dare_current_institution');

    if (saved) {
      try {
        setSavedBooks(JSON.parse(saved));
      } catch (e) {
        console.error("Failed to parse saved books", e);
      }
    }

    if (savedInst) {
      try {
        setCurrentInstitution(JSON.parse(savedInst));
      } catch (e) {
        console.error("Failed to parse saved institution", e);
      }
    }
  }, []);

  // Save books to localStorage
  useEffect(() => {
    localStorage.setItem('dare_saved_resources', JSON.stringify(savedBooks));
  }, [savedBooks]);

  // Save institution to localStorage
  useEffect(() => {
    if (currentInstitution) {
      localStorage.setItem('dare_current_institution', JSON.stringify(currentInstitution));
    } else {
      localStorage.removeItem('dare_current_institution');
    }
  }, [currentInstitution]);

  const handleBookClick = (book: Book) => {
    setSelectedBook(book);
    setIsDetailsOpen(true);
  };

  const handleToggleSave = (e: React.MouseEvent, book: Book) => {
    e.stopPropagation();
    const isAlreadySaved = savedBooks.some(s => s.id === book.id);
    if (isAlreadySaved) {
      setSavedBooks(prev => prev.filter(s => s.id !== book.id));
      toast.info(`"${book.title}" removed from collection.`);
    } else {
      setSavedBooks(prev => [...prev, { ...book, isSaved: true }]);
      toast.success(`"${book.title}" saved to your collection!`);
    }
  };

  const handleAddBook = (_newBook: Book) => {
    setRefreshTrigger(prev => prev + 1);
    toast.success("Resource submitted for peer review!");
  };

  const handleSelectInstitution = (inst: Institution) => {
    setCurrentInstitution(inst);
    toast.success(`Welcome to Dare Digital Library, ${inst.name} student!`, {
      description: "Your institutional access has been activated."
    });
  };

  const handleLogout = () => {
    setCurrentInstitution(null);
    toast.info("Logged out of institutional access.");
  };

  if (!currentInstitution) {
    return (
      <div className="min-h-screen">
        <LandingPage onSelectInstitution={handleSelectInstitution} />
        <Toaster position="bottom-right" richColors />
      </div>
    );
  }

  return (
    <AppLayout
      currentInstitution={currentInstitution}
      savedCount={savedBooks.length}
      onLogout={handleLogout}
      searchQuery={searchQuery}
      onSearchChange={setSearchQuery}
      onAddClick={() => setIsAddModalOpen(true)}
      onSavedClick={() => navigate('/collection')}
    >
      <Routes>
        <Route path="/" element={
          <DashboardPage 
            key={refreshTrigger}
            searchQuery={searchQuery}
            onBookClick={handleBookClick}
            savedBooks={savedBooks}
            onToggleSave={handleToggleSave}
            currentInstitution={currentInstitution}
          />
        } />
        <Route path="/collection" element={
          <CollectionPage 
            searchQuery={searchQuery}
            onBookClick={handleBookClick}
            savedBooks={savedBooks}
            onToggleSave={handleToggleSave}
            currentInstitution={currentInstitution}
          />
        } />
        <Route path="/ai-tutor" element={<AITutorPage />} />
        <Route path="/library" element={
          <DashboardPage 
            searchQuery={searchQuery}
            onBookClick={handleBookClick}
            savedBooks={savedBooks}
            onToggleSave={handleToggleSave}
            currentInstitution={currentInstitution}
          />
        } />
        <Route path="/curriculum" element={<CurriculumPage />} />
        <Route path="/notes" element={<NotesPage />} />
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>

      <BookDetails
        book={selectedBook}
        isOpen={isDetailsOpen}
        onClose={() => setIsDetailsOpen(false)}
      />

      <AddBookModal
        isOpen={isAddModalOpen}
        onClose={() => setIsAddModalOpen(false)}
        onAdd={handleAddBook}
      />
    </AppLayout>
  );
}

function App() {
  return (
    <BrowserRouter>
      <AppContent />
    </BrowserRouter>
  );
}

export default App;
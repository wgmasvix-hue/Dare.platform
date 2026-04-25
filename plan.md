# Dedicated Navigation Implementation Plan

Implement a robust, intuitive, and accessible navigation system for the Dare Digital Library using `react-router-dom` and shadcn/ui components.

## 1. Routing Setup
- Wrap the application in `BrowserRouter` in `src/main.tsx` or `src/App.tsx`.
- Define semantic routes:
    - `/` (Dashboard/Discovery)
    - `/library` (Institutional Library)
    - `/collection` (My Personal Collection)
    - `/ai-tutor` (AI Research Assistant)
    - `/curriculum` (Education 5.0 Hub)
    - `/notes` (Research Notes)

## 2. Navigation Components
- **AppSidebar**: A new sidebar component using shadcn/ui primitives.
    - Navigation groups: Main, Research, Institutional.
    - Mobile-responsive (collapsible/sheet).
    - Active link state styling.
- **AppLayout**: A wrapper component to provide consistent layout for authenticated users.
    - Includes `SidebarProvider`, `AppSidebar`, and a content area with `Navbar`.
- **Navbar Enhancement**:
    - Add `SidebarTrigger` for mobile/tablet.
    - Move primary navigation links to the sidebar.
    - Keep global search and user/institution profile in the navbar.

## 3. Page Refactoring
- Create lightweight page wrappers in `src/pages/` to host existing dashboard and library logic.
- Ensure state (selected book, saved books) is accessible where needed, potentially lifting it or using context if complex, but sticking to prop passing where simple.

## 4. Implementation Steps
1. Create `src/components/layout/AppSidebar.tsx`.
2. Create `src/components/layout/AppLayout.tsx`.
3. Create page wrappers in `src/pages/`.
4. Update `src/App.tsx` with `Routes` and `Route`.
5. Update `src/components/layout/Navbar.tsx` for sidebar integration.
6. Verify responsive behavior and navigation flow.

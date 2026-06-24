import React from "react";

interface TabsProps {
  children: React.ReactNode;
  value?: string;
  onValueChange?: (value: string) => void;
  className?: string;
}

interface TabsListProps {
  children: React.ReactNode;
  className?: string;
}

interface TabsTriggerProps {
  children: React.ReactNode;
  value?: string;
  className?: string;
}

interface TabsContentProps {
  children: React.ReactNode;
  value?: string;
  className?: string;
}

export const Tabs: React.FC<TabsProps> = ({ children }) => {
  return <div>{children}</div>;
};

export const TabsList: React.FC<TabsListProps> = ({ children }) => {
  return <div>{children}</div>;
};

export const TabsTrigger: React.FC<TabsTriggerProps> = ({ children }) => {
  return <button>{children}</button>;
};

export const TabsContent: React.FC<TabsContentProps> = ({ children }) => {
  return <div>{children}</div>;
};

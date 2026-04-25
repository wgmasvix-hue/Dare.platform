# Dare Digital Library - Deployment Guide

This guide explains how to package the application for Windows and deploy it to a server.

## 1. Windows Installation (Desktop App)

To create a standalone Windows installer (`.exe`):

### Prerequisites
- [Node.js](https://nodejs.org/) installed on your machine.
- Run `npm install` (or `bun install`) to install dependencies.

### Packaging
Run the following command in your terminal:
```bash
npm run electron:build
```
This will:
1. Build the React frontend.
2. Package the application using Electron.
3. Generate a Windows installer in the `dist-electron/` folder.

You will find:
- `Dare Digital Library Setup 1.0.0.exe` (Standard Installer)
- `Dare Digital Library 1.0.0.exe` (Portable Version)

---

## 2. Server Deployment (Docker)

To host the library on a local server or cloud provider:

### Prerequisites
- [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) installed.

### Deploy
Run:
```bash
docker-compose up -d --build
```
The application will be available at `http://localhost:8080`.

---

## 3. Localhost Deployment (Manual)

If you just want to run it locally for testing:

1. Install dependencies: `npm install`
2. Build the project: `npm run build`
3. Serve the `dist` folder using any static server:
   ```bash
   npx serve -s dist
   ```

---

## Configuration Note
The application is configured with `base: './'` in `vite.config.ts`. This ensures that all assets load correctly regardless of whether the app is running in a browser, a subdirectory on a server, or via the `file://` protocol in Electron.
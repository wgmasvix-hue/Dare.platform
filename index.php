<?php
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZIM-REPO | International Digital Library</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.468.0/font/lucide.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .animate-spin { animation: spin 1s linear infinite; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .bg-zim-blue { background-color: #0c1c38; }
        .text-zim-gold { color: #d4af37; }
        .border-zim-gold { border-color: #d4af37; }
        .selection-zim { selection-background-color: rgba(212, 175, 55, 0.2); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 h-full font-sans selection:bg-indigo-100" x-data="libraryApp()" x-init="init()">
    
    <!-- Top Bar -->
    <div class="bg-zim-blue text-white py-2 px-4 text-[10px] font-bold uppercase tracking-[0.2em] flex justify-between items-center border-b border-white/10">
        <div class="flex items-center gap-4">
            <span class="flex items-center gap-1"><i class="lucide-globe w-3 h-3 text-zim-gold"></i> International Network</span>
            <span class="flex items-center gap-1"><i class="lucide-shield-check w-3 h-3 text-zim-gold"></i> Verified Academic Library</span>
        </div>
        <div class="hidden md:block">Zimbabwe Higher Education Infrastructure | Education 5.0 Compliant</div>
    </div>

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white border-b border-slate-200">
        <div class="container mx-auto px-4 h-20 flex items-center justify-between gap-8">
            <div class="flex items-center gap-4 cursor-pointer" @click="resetSearch()">
                <div class="w-12 h-12 bg-zim-blue rounded-xl flex items-center justify-center text-white border-b-4 border-zim-gold">
                    <img src="https://storage.googleapis.com/dala-prod-public-storage/generated-images/ac009de3-7976-4350-ae23-5fdb18d3f125/zim-edu-logo-a9e9716c-1776784988805.webp" class="w-10 h-10 object-contain rounded-lg">
                </div>
                <div class="flex flex-col">
                    <span class="font-black text-2xl tracking-tighter text-zim-blue leading-none">ZIM-REPO</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">National Library</span>
                </div>
            </div>

            <div class="flex-1 max-w-xl relative hidden lg:block">
                <i class="lucide-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                <input 
                    type="text" 
                    placeholder="Search across 1.5M+ international & local resources..." 
                    class="w-full bg-slate-100 border border-slate-200 rounded-2xl py-3 pl-12 pr-4 focus:ring-4 focus:ring-zim-blue/5 focus:bg-white focus:border-zim-blue transition-all outline-none text-sm font-medium"
                    x-model="searchQuery"
                    @input.debounce.500ms="search(true)"
                >
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden xl:flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase text-slate-400">Institutional Access</span>
                    <select x-model="selectedInstitution" @change="search(true)" class="bg-transparent font-bold text-sm text-zim-blue outline-none cursor-pointer">
                        <option value="">Global Network</option>
                        <template x-for="inst in config.institutions" :key="inst.id">
                            <option :value="inst.id" x-text="inst.name"></option>
                        </template>
                    </select>
                </div>
                <button @click="showAddModal = true" class="bg-zim-blue hover:bg-slate-800 text-white px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-lg shadow-zim-blue/20 flex items-center gap-2">
                    <i class="lucide-upload-cloud w-4 h-4 text-zim-gold"></i>
                    <span>Submit Resource</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-zim-blue py-32 text-white">
            <div class="absolute inset-0 opacity-40">
                <img src="https://storage.googleapis.com/dala-prod-public-storage/generated-images/ac009de3-7976-4350-ae23-5fdb18d3f125/hero-bg-4cf6c973-1776784989226.webp" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-zim-blue via-zim-blue/80 to-transparent"></div>
            </div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 bg-zim-gold/20 backdrop-blur-md px-4 py-2 rounded-full border border-zim-gold/30 text-zim-gold text-xs font-black uppercase tracking-widest mb-8">
                        <span class="w-2 h-2 bg-zim-gold rounded-full animate-pulse"></span>
                        Unified Knowledge Gateway
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black tracking-tighter mb-8 leading-[1.05] font-serif">
                        Powering <span class="text-zim-gold">Zimbabwe's</span> Academic Future.
                    </h1>
                    <p class="text-slate-300 text-xl max-w-xl mb-12 leading-relaxed">
                        The definitive modern digital library for universities, polytechnics, and colleges. Integrating international research with local curriculum expertise.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-white/10 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 flex-1 min-w-[200px]">
                            <p class="text-zim-gold text-3xl font-black mb-1">1.5M+</p>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Resources</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 flex-1 min-w-[200px]">
                            <p class="text-zim-gold text-3xl font-black mb-1">Education 5.0</p>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Alignment</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Curriculum Navigation -->
        <div class="bg-white border-b border-slate-200">
            <div class="container mx-auto px-4 overflow-x-auto no-scrollbar">
                <div class="flex items-center h-20 gap-8 whitespace-nowrap">
                    <button 
                        @click="selectedCurriculum = ''; search(true)"
                        :class="!selectedCurriculum ? 'text-zim-blue border-zim-blue' : 'text-slate-400 border-transparent'"
                        class="h-full border-b-4 font-black text-sm uppercase tracking-widest flex items-center transition-all"
                    >
                        All Categories
                    </button>
                    <template x-for="cat in config.curriculum" :key="cat.id">
                        <button 
                            @click="selectedCurriculum = cat.id; search(true)"
                            :class="selectedCurriculum === cat.id ? 'text-zim-blue border-zim-blue' : 'text-slate-400 border-transparent hover:text-zim-blue'"
                            class="h-full border-b-4 font-black text-sm uppercase tracking-widest flex items-center transition-all"
                            x-text="cat.name"
                        ></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Search Results Header -->
        <div class="container mx-auto px-4 mt-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 text-zim-blue">
                    <i class="lucide-layers w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tighter text-zim-blue" x-text="loading ? 'Syncing Library...' : (books.length + ' Resources Found')"></h2>
                    <p class="text-slate-400 text-sm font-medium" x-show="selectedCurriculum" x-text="'Filtered by ' + config.curriculum.find(c => c.id === selectedCurriculum)?.name"></p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                <template x-for="f in filters" :key="f.id">
                    <button 
                        @click="activeFilter = f.id; search(true)"
                        :class="activeFilter === f.id ? 'bg-zim-blue text-white ring-4 ring-zim-blue/10' : 'bg-white text-slate-600 hover:bg-slate-100 border-slate-200'"
                        class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap shadow-sm border"
                        x-text="f.name"
                    ></button>
                </template>
            </div>
        </div>

        <!-- Book Grid -->
        <div class="container mx-auto px-4 py-12">
            <div x-show="loading && books.length === 0" class="flex flex-col items-center justify-center py-24 gap-6">
                <div class="relative">
                    <div class="w-20 h-20 border-4 border-zim-blue/10 border-t-zim-blue rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="lucide-database w-8 h-8 text-zim-blue"></i>
                    </div>
                </div>
                <p class="text-zim-blue font-black uppercase tracking-widest text-sm animate-pulse">Syncing International Nodes...</p>
            </div>

            <div x-show="books.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-8">
                <template x-for="book in books" :key="book.id">
                    <div 
                        class="group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border border-slate-100 cursor-pointer flex flex-col h-full relative"
                        @click="openDetails(book)"
                    >
                        <div class="relative aspect-[4/5] overflow-hidden bg-slate-100">
                            <img :src="book.cover_image" :alt="book.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-zim-blue/90 via-zim-blue/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="absolute bottom-6 left-6 right-6 flex justify-center">
                                    <span class="bg-zim-gold text-zim-blue px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl">Quick View</span>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider text-zim-blue border border-slate-100 shadow-sm" x-text="book.source"></div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-2 h-2 bg-zim-gold rounded-full"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 truncate max-w-[150px]" x-text="book.category"></span>
                            </div>
                            <h3 class="font-black text-lg leading-tight mb-2 group-hover:text-zim-blue transition-colors line-clamp-2 font-serif" x-text="book.title"></h3>
                            <p class="text-sm text-slate-500 font-medium mt-auto" x-text="book.author"></p>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50 flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <span class="flex items-center gap-1.5"><i class="lucide-calendar w-3 h-3"></i> <span x-text="book.publication_year"></span></span>
                            <span class="flex items-center gap-1.5 text-zim-blue bg-zim-blue/5 px-2 py-1 rounded-lg"><i class="lucide-book-open w-3 h-3"></i> <span x-text="book.pages"></span></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="!loading && books.length === 0" class="text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="lucide-search-x w-12 h-12 text-slate-200"></i>
                </div>
                <h3 class="text-3xl font-black text-zim-blue mb-4">No Resources Found</h3>
                <p class="text-slate-500 max-w-sm mx-auto mb-8 font-medium">Try broadening your search or switching to the global library filter.</p>
                <button @click="resetSearch()" class="bg-zim-blue text-white px-8 py-3 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-slate-800 transition-all">Show All Sources</button>
            </div>

            <!-- Loading More -->
            <div x-show="loadingMore" class="flex justify-center py-20">
                <div class="w-10 h-10 border-4 border-zim-blue/10 border-t-zim-blue rounded-full animate-spin"></div>
            </div>

            <div id="sentinel" class="h-10"></div>
        </div>
    </main>

    <footer class="bg-zim-blue py-32 text-slate-400">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-4 mb-8 text-white font-black text-3xl">
                        <img src="https://storage.googleapis.com/dala-prod-public-storage/generated-images/ac009de3-7976-4350-ae23-5fdb18d3f125/zim-edu-logo-a9e9716c-1776784988805.webp" class="w-12 h-12 object-contain bg-white p-1 rounded-xl">
                        ZIM-REPO
                    </div>
                    <p class="max-w-md mb-10 text-lg leading-relaxed text-slate-400">
                        The national digital library and academic library platform. Empowering Zimbabwean higher education institutions with modern knowledge infrastructure.
                    </p>
                    <div class="flex gap-4">
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10 flex-1">
                            <p class="text-zim-gold text-sm font-black mb-1">Education 5.0</p>
                            <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Standard</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10 flex-1">
                            <p class="text-white text-sm font-black mb-1">256-bit</p>
                            <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Encryption</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-black uppercase tracking-widest text-xs mb-8">Resources</h4>
                    <ul class="space-y-4 text-sm font-bold uppercase tracking-widest">
                        <li><a href="#" class="hover:text-zim-gold transition-colors">International Library</a></li>
                        <li><a href="#" class="hover:text-zim-gold transition-colors">Institutional Journals</a></li>
                        <li><a href="#" class="hover:text-zim-gold transition-colors">Research Papers</a></li>
                        <li><a href="#" class="hover:text-zim-gold transition-colors">Open Access Data</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-black uppercase tracking-widest text-xs mb-8">Support</h4>
                    <ul class="space-y-4 text-sm font-bold uppercase tracking-widest">
                        <li><a href="#" class="hover:text-zim-gold transition-colors">Contact Admin</a></li>
                        <li><a href="#" class="hover:text-zim-gold transition-colors">Technical Docs</a></li>
                        <li><a href="#" class="hover:text-zim-gold transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-zim-gold transition-colors">Terms of Use</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 text-[10px] font-black uppercase tracking-[0.2em]">
                <div>&copy; <?php echo date('Y'); ?> Zimbabwe Higher Education Network. All Rights Reserved.</div>
                <div class="flex items-center gap-6">
                    <span>Powered by ZIMCHE Framework</span>
                    <span>v2.4.0 Build</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Book Details Modal -->
    <div x-show="showDetails" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-zim-blue/95 backdrop-blur-xl" @click.self="closeDetails()">
        <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-6xl h-[90vh] overflow-hidden flex flex-col md:flex-row relative">
            <button @click="closeDetails()" class="absolute top-6 right-6 z-50 bg-zim-blue/10 hover:bg-zim-blue/20 p-3 rounded-2xl text-zim-blue transition-colors">
                <i class="lucide-x w-6 h-6"></i>
            </button>

            <!-- Sidebar Info -->
            <div class="md:w-5/12 bg-zim-blue relative overflow-hidden">
                <img :src="selectedBook?.cover_image" class="w-full h-full object-cover opacity-50 scale-105 blur-sm absolute inset-0">
                <div class="relative h-full p-12 flex flex-col justify-end text-white z-10 bg-gradient-to-t from-zim-blue via-zim-blue/40 to-transparent">
                    <div class="bg-zim-gold text-zim-blue px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest w-fit mb-4" x-text="selectedBook?.source"></div>
                    <span class="text-zim-gold/80 text-xs font-black uppercase tracking-[0.2em] mb-4 block" x-text="selectedBook?.category"></span>
                    <h2 class="text-4xl font-black tracking-tighter leading-none mb-6 font-serif" x-text="selectedBook?.title"></h2>
                    <p class="text-slate-300 font-bold text-lg mb-8" x-text="'By ' + selectedBook?.author"></p>
                    
                    <div class="flex gap-4">
                        <div class="bg-white/10 p-4 rounded-2xl border border-white/10 flex-1 text-center">
                            <p class="text-zim-gold font-black text-xl mb-0" x-text="selectedBook?.publication_year"></p>
                            <p class="text-[9px] uppercase font-black text-slate-400">Year</p>
                        </div>
                        <div class="bg-white/10 p-4 rounded-2xl border border-white/10 flex-1 text-center">
                            <p class="text-zim-gold font-black text-xl mb-0" x-text="selectedBook?.pages"></p>
                            <p class="text-[9px] uppercase font-black text-slate-400">Pages</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="md:w-7/12 flex flex-col bg-white">
                <div class="border-b px-12 h-24 flex items-center gap-10 overflow-x-auto no-scrollbar">
                    <button @click="detailTab = 'overview'" :class="detailTab === 'overview' ? 'text-zim-blue border-zim-blue' : 'text-slate-400 border-transparent hover:text-zim-blue'" class="h-full border-b-4 font-black text-xs uppercase tracking-[0.2em] flex items-center gap-2 whitespace-nowrap">
                        <i class="lucide-info w-4 h-4"></i> Overview
                    </button>
                    <button @click="loadContentTab()" :class="detailTab === 'read' ? 'text-zim-blue border-zim-blue' : 'text-slate-400 border-transparent hover:text-zim-blue'" class="h-full border-b-4 font-black text-xs uppercase tracking-[0.2em] flex items-center gap-2 whitespace-nowrap">
                        <i class="lucide-file-text w-4 h-4"></i> Research Link
                    </button>
                    <button @click="detailTab = 'ai'" :class="detailTab === 'ai' ? 'text-zim-blue border-zim-blue' : 'text-slate-400 border-transparent hover:text-zim-blue'" class="h-full border-b-4 font-black text-xs uppercase tracking-[0.2em] flex items-center gap-2 whitespace-nowrap">
                        <i class="lucide-sparkles w-4 h-4"></i> AI Abstract
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-12">
                    <!-- Overview Tab -->
                    <div x-show="detailTab === 'overview'" class="space-y-10 animate-in fade-in duration-500">
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Resource Description</h4>
                            <p class="text-slate-600 leading-relaxed text-xl font-medium" x-text="selectedBook?.description"></p>
                        </div>
                        <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 space-y-4">
                            <h5 class="text-zim-blue font-black uppercase tracking-widest text-[10px]">Academic Significance</h5>
                            <p class="text-sm text-slate-500 leading-relaxed">This resource is mapped to the current Zimbabwean higher education curriculum standards and provides essential foundational knowledge for institutional research.</p>
                        </div>
                        <div class="pt-8 flex gap-4">
                            <button @click="loadContentTab()" class="flex-1 bg-zim-blue text-white h-16 rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-zim-blue/30 hover:bg-slate-800 transition-all flex items-center justify-center gap-3">
                                <i class="lucide-external-link w-5 h-5 text-zim-gold"></i> Access Library
                            </button>
                            <button class="w-16 h-16 border-2 border-slate-100 rounded-2xl flex items-center justify-center hover:bg-slate-50 transition-all text-slate-400">
                                <i class="lucide-bookmark w-6 h-6"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Read Tab -->
                    <div x-show="detailTab === 'read'" class="h-full animate-in fade-in duration-500">
                        <div x-show="loadingContent" class="flex flex-col items-center justify-center h-full gap-6 py-20">
                            <div class="w-12 h-12 border-4 border-zim-blue/10 border-t-zim-blue rounded-full animate-spin"></div>
                            <p class="text-slate-400 font-black uppercase tracking-widest text-[10px]">Authorizing Access...</p>
                        </div>
                        <div x-show="!loadingContent" class="prose max-w-none">
                            <div x-show="bookContent" class="font-serif text-lg leading-relaxed whitespace-pre-wrap p-6 bg-slate-50 rounded-3xl" x-text="bookContent"></div>
                            <div x-show="!bookContent" class="text-center py-20 space-y-10">
                                <div class="w-24 h-24 bg-zim-blue/5 rounded-full flex items-center justify-center mx-auto">
                                    <i class="lucide-shield-check w-10 h-10 text-zim-blue"></i>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-3xl font-black text-zim-blue tracking-tighter">Remote Institutional Link</h4>
                                    <p class="text-slate-500 max-w-xs mx-auto text-lg">You are about to be redirected to the official source library for this academic resource.</p>
                                </div>
                                <a :href="selectedBook?.read_link" target="_blank" class="inline-flex bg-zim-blue text-white px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-xs gap-3 shadow-xl shadow-zim-blue/20">
                                    <i class="lucide-external-link w-5 h-5 text-zim-gold"></i> Launch External Library
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- AI Tab -->
                    <div x-show="detailTab === 'ai'" class="animate-in fade-in duration-500">
                        <div x-show="!aiSummary && !loadingAI" class="text-center py-12 space-y-10">
                            <div class="w-24 h-24 bg-zim-gold/10 rounded-full flex items-center justify-center mx-auto">
                                <i class="lucide-sparkles w-12 h-12 text-zim-gold"></i>
                            </div>
                            <div class="space-y-4">
                                <h4 class="text-3xl font-black text-zim-blue tracking-tighter">Analyze with AI</h4>
                                <p class="text-slate-500 max-w-sm mx-auto text-lg font-medium">Generate a curriculum-aligned executive summary using our deep-learning academic model.</p>
                            </div>
                            <button @click="generateAI()" class="bg-zim-blue text-white px-10 py-5 rounded-3xl font-black uppercase tracking-widest text-xs shadow-xl shadow-zim-blue/20 flex items-center gap-3 mx-auto">
                                <i class="lucide-wand-2 w-5 h-5 text-zim-gold"></i> Start Academic Analysis
                            </button>
                        </div>

                        <div x-show="loadingAI" class="flex flex-col items-center justify-center py-20 gap-6">
                            <div class="w-16 h-16 border-4 border-zim-gold/10 border-t-zim-gold rounded-full animate-spin"></div>
                            <p class="text-slate-400 font-black uppercase tracking-widest text-[10px]">Processing Contextual Metadata...</p>
                        </div>

                        <div x-show="aiSummary" class="space-y-12">
                            <div class="bg-zim-blue text-white p-10 rounded-[3rem] shadow-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-zim-gold/10 rounded-full -mr-16 -mt-16"></div>
                                <div class="flex items-center gap-2 mb-6 text-zim-gold font-black text-[10px] uppercase tracking-[0.3em]">
                                    <i class="lucide-bot w-4 h-4"></i> Executive Summary
                                </div>
                                <p class="text-white font-serif text-2xl italic leading-relaxed" x-text="aiSummary?.brief"></p>
                            </div>
                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100">
                                    <h5 class="font-black mb-6 flex items-center gap-2 text-[10px] uppercase tracking-widest text-zim-blue">
                                        <i class="lucide-layers w-4 h-4 text-zim-gold"></i> Key Knowledge Nodes
                                    </h5>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="theme in aiSummary?.themes">
                                            <span class="bg-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 shadow-sm" x-text="theme"></span>
                                        </template>
                                    </div>
                                </div>
                                <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100">
                                    <h5 class="font-black mb-6 flex items-center gap-2 text-[10px] uppercase tracking-widest text-zim-blue">
                                        <i class="lucide-book-open-check w-4 h-4 text-zim-gold"></i> Research Value
                                    </h5>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium" x-text="aiSummary?.whyRead"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Resource Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-zim-blue/90 backdrop-blur-md">
        <div class="bg-white p-12 rounded-[3rem] shadow-2xl w-full max-w-2xl space-y-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-zim-blue via-zim-gold to-zim-blue"></div>
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-4xl font-black tracking-tighter text-zim-blue mb-2 font-serif">Submit Content</h2>
                    <p class="text-slate-500 font-medium">Contribute to the national knowledge network.</p>
                </div>
                <button @click="showAddModal = false" class="text-slate-300 hover:text-zim-blue transition-colors">
                    <i class="lucide-x w-8 h-8"></i>
                </button>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Institutional Branch</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 outline-none focus:ring-4 focus:ring-zim-blue/5 text-sm font-bold">
                        <option>Select Institution...</option>
                        <template x-for="inst in config.institutions" :key="inst.id">
                            <option :value="inst.id" x-text="inst.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Curriculum Area</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 outline-none focus:ring-4 focus:ring-zim-blue/5 text-sm font-bold">
                        <template x-for="cat in config.curriculum" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-span-2 space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Document Title</label>
                    <input type="text" placeholder="e.g. Heritage Based Education 5.0 Implementation" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 outline-none focus:ring-4 focus:ring-zim-blue/5 text-sm font-bold">
                </div>
                <div class="col-span-2 space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Author(s)</label>
                    <input type="text" placeholder="Separate multiple authors with commas" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 outline-none focus:ring-4 focus:ring-zim-blue/5 text-sm font-bold">
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button @click="showAddModal = false" class="flex-1 px-8 py-5 border-2 border-slate-100 rounded-2xl font-black uppercase tracking-widest text-xs text-slate-400 hover:bg-slate-50 transition-all">Discard</button>
                <button @click="showAddModal = false; alert('Submission received. Undergoing peer review.')" class="flex-1 px-8 py-5 bg-zim-blue text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-slate-800 shadow-xl shadow-zim-blue/20 transition-all">Submit for Review</button>
            </div>
        </div>
    </div>

    <script>
        function libraryApp() {
            return {
                books: [],
                searchQuery: '',
                activeFilter: 'all',
                selectedInstitution: '',
                selectedCurriculum: '',
                config: { institutions: [], curriculum: [] },
                page: 1,
                loading: false,
                loadingMore: false,
                hasMore: true,
                showDetails: false,
                selectedBook: null,
                detailTab: 'overview',
                bookContent: null,
                loadingContent: false,
                aiSummary: null,
                loadingAI: false,
                showAddModal: false,
                filters: [
                    { id: 'all', name: 'Global Network' },
                    { id: 'springer', name: 'Springer AI' },
                    { id: 'dspace', name: 'DSpace Nodes' },
                    { id: 'loc', name: 'Library of Congress' },
                    { id: 'openstax', name: 'OpenStax Edu' },
                    { id: 'doaj', name: 'DOAJ Journals' },
                    { id: 'internetarchive', name: 'Archive.org' }
                ],

                init() {
                    // Fetch config first
                    fetch('api.php?action=get_config')
                        .then(res => res.json())
                        .then(data => {
                            this.config = data;
                        });

                    this.search(true);

                    // Intersection Observer for Infinite Scroll
                    const observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            this.loadMore();
                        }
                    }, { threshold: 0.1 });
                    
                    observer.observe(document.getElementById('sentinel'));
                },

                resetSearch() {
                    this.searchQuery = '';
                    this.activeFilter = 'all';
                    this.selectedInstitution = '';
                    this.selectedCurriculum = '';
                    this.search(true);
                },

                search(initial = false) {
                    if (initial) {
                        this.page = 1;
                        this.books = [];
                        this.loading = true;
                        this.hasMore = true;
                    } else {
                        this.loadingMore = true;
                    }

                    const params = new URLSearchParams({
                        action: 'fetch_books',
                        query: this.searchQuery,
                        filter: this.activeFilter,
                        page: this.page,
                        institution: this.selectedInstitution,
                        curriculum: this.selectedCurriculum
                    });

                    fetch(`api.php?${params.toString()}`)
                        .then(res => res.json())
                        .then(data => {
                            if (initial) {
                                this.books = data.books;
                            } else {
                                this.books = [...this.books, ...data.books];
                            }
                            if (data.books.length < 5) this.hasMore = false;
                        })
                        .finally(() => {
                            this.loading = false;
                            this.loadingMore = false;
                        });
                },

                loadMore() {
                    if (this.loading || this.loadingMore || !this.hasMore) return;
                    this.page++;
                    this.search(false);
                },

                openDetails(book) {
                    this.selectedBook = book;
                    this.showDetails = true;
                    this.detailTab = 'overview';
                    this.bookContent = null;
                    this.aiSummary = null;
                },

                closeDetails() {
                    this.showDetails = false;
                    this.selectedBook = null;
                },

                loadContentTab() {
                    this.detailTab = 'read';
                    if (this.bookContent) return;
                    
                    // Only try to fetch if it's likely a text file (Gutenberg)
                    if (this.selectedBook.source !== 'gutenberg') return;

                    this.loadingContent = true;
                    fetch(`api.php?action=fetch_content&url=${encodeURIComponent(this.selectedBook.read_link)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.content && data.content.length > 100) {
                                this.bookContent = data.content;
                            }
                        })
                        .finally(() => this.loadingContent = false);
                },

                generateAI() {
                    this.loadingAI = true;
                    fetch('api.php?action=generate_summary', {
                        method: 'POST',
                        body: JSON.stringify({ book: this.selectedBook }),
                        headers: { 'Content-Type': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.aiSummary = data;
                    })
                    .finally(() => this.loadingAI = false);
                }
            }
        }
    </script>
</body>
</html>
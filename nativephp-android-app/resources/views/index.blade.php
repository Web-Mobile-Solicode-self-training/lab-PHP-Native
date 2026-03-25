<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookFinder | NativePHP Pro</title>
    
    <!-- Styles & Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: '#FFD700',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .book-card-shadow {
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</head>
<body class="bg-[#020617] text-slate-200 h-full selection:bg-primary/30" 
      x-data="bookApp(@js($books))" 
      :class="{ 'overflow-hidden': isModalOpen || isMenuOpen }">

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 glass-effect border-b border-white/5 py-4 px-6 md:px-12 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-primary/10 rounded-xl">
                <i data-lucide="book-open" class="text-primary w-6 h-6"></i>
            </div>
            <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                BookFinder
            </span>
        </div>

        <!-- Desktop Search -->
        <div class="hidden md:flex flex-1 max-w-md mx-8 relative">
            <form action="{{ route('home') }}" method="GET" class="w-full relative">
                <input type="text" name="q" placeholder="Search millions of books..." value="{{ request('q') }}"
                       class="w-full bg-white/5 border border-white/10 rounded-2xl py-2.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all text-white">
                <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 hover:text-primary transition-colors"></i>
                </button>
            </form>
        </div>

        <div class="flex items-center gap-4">
            <button @click="isMenuOpen = true" class="p-2.5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Mobile Search -->
        <div class="md:hidden relative mb-8">
            <form action="{{ route('home') }}" method="GET" class="relative w-full">
                <input type="text" name="q" placeholder="Titles, authors, keywords..." value="{{ request('q') }}"
                       class="w-full bg-white/5 border border-white/10 rounded-2xl py-3.5 pl-14 pr-4 text-base focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all text-white">
                <button type="submit" class="absolute left-5 top-1/2 -translate-y-1/2">
                    <i data-lucide="search" class="w-5 h-5 text-slate-500 hover:text-primary transition-colors"></i>
                </button>
            </form>
        </div>

        <!-- Section Header -->
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white mb-1">{{ $resultsTitle }}</h2>
                <p class="text-slate-500 text-sm">Discover your next academic adventure</p>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8">
            @foreach($books as $index => $book)
                <div class="group relative flex flex-col items-center text-center animate-in fade-in slide-in-from-bottom-4 duration-500"
                     style="animation-delay: {{ $index * 50 }}ms"
                     @click="openInfo(books[{{ $index }}])">
                    
                    <div class="relative w-full aspect-[2/3] rounded-3xl overflow-hidden book-card-shadow transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-primary/20 cursor-pointer">
                        <img src="{{ Str::replace('http:', 'https:', $book['volumeInfo']['imageLinks']['thumbnail'] ?? $book['volumeInfo']['imageLinks']['smallThumbnail'] ?? 'https://via.placeholder.com/300x450?text=No+Cover') }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             alt="{{ $book['volumeInfo']['title'] ?? 'Unknown' }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </div>

                    <div class="mt-4 w-full px-2">
                        <h3 class="font-semibold text-white text-sm line-clamp-1 group-hover:text-primary transition-colors cursor-pointer">{{ $book['volumeInfo']['title'] ?? 'Unknown Title' }}</h3>
                        <p class="text-slate-500 text-xs mt-1 truncate">{{ implode(', ', $book['volumeInfo']['authors'] ?? ['Unknown Author']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if(empty($books))
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="p-6 rounded-full bg-white/5 mb-6 border border-white/10">
                <i data-lucide="search-x" class="w-12 h-12 text-slate-600"></i>
            </div>
            <h3 class="text-xl font-bold text-white">No books found</h3>
            <p class="text-slate-500 mt-2">Try a different search term or genre.</p>
        </div>
        @endif
    </main>

    <!-- Modal Drawer -->
    <div x-show="isModalOpen" 
         class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <div class="absolute inset-0 bg-black/60 backdrop-blur-md" @click="isModalOpen = false"></div>
        
        <div class="relative w-full max-w-2xl bg-[#0f172a] sm:rounded-[40px] rounded-t-[40px] border-t sm:border border-white/10 overflow-hidden shadow-2xl"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="translate-y-full sm:scale-95"
             x-transition:enter-end="translate-y-0 sm:scale-100">
            
            <div class="absolute top-6 right-6 z-10">
                <button @click="isModalOpen = false" class="p-2 rounded-full bg-black/40 hover:bg-black/60 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex flex-col md:flex-row h-full">
                <div class="w-full md:w-2/5 aspect-[3/4] md:aspect-auto">
                    <img :src="getCover(selectedBook, 'L')" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 p-8 md:p-12 overflow-y-auto max-h-[100vw] md:max-h-[600px]">
                    <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-[10px] font-bold uppercase tracking-widest" x-text="selectedBook?.volumeInfo?.categories?.[0] || 'Book'"></span>
                    <h2 class="text-2xl font-bold text-white mt-4 leading-tight" x-text="selectedBook?.volumeInfo?.title"></h2>
                    <p class="text-primary font-medium mt-2" x-text="getAuthor(selectedBook)"></p>
                    
                    <div class="mt-8 space-y-4">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Description</h4>
                        <div class="text-slate-400 text-sm leading-relaxed prose prose-invert" x-html="selectedBook?.volumeInfo?.description || 'No description available for this volume.'"></div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <a :href="selectedBook?.volumeInfo?.previewLink" target="_blank" class="flex-1 bg-primary text-dark py-4 rounded-2xl font-bold text-center hover:shadow-lg hover:shadow-primary/20 transition-all active:scale-95">Read Preview</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="isMenuOpen" class="fixed inset-0 z-[100]" x-cloak>
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="isMenuOpen = false"></div>
        <div x-show="isMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             class="absolute right-0 top-0 bottom-0 w-80 bg-[#0f172a] p-8 border-l border-white/5">
            <div class="flex justify-between items-center mb-12">
                <span class="font-bold text-xl text-white">Navigation</span>
                <button @click="isMenuOpen = false"><i data-lucide="arrow-right" class="w-6 h-6"></i></button>
            </div>
            
            <nav class="space-y-6">
                <!-- Using GET requests for navigation inside backend MVC -->
                <template x-for="item in ['Programming', 'Design', 'Business', 'Philosophy', 'Science']">
                    <a :href="'/?q=' + item" class="flex items-center gap-4 text-slate-400 hover:text-white transition-colors group w-full text-left">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary/40 group-hover:bg-primary transition-colors"></span>
                        <span class="text-lg font-medium" x-text="item"></span>
                    </a>
                </template>
            </nav>

            <div class="absolute bottom-8 left-8 right-8">
                <div class="p-6 rounded-3xl bg-primary/10 border border-primary/20 text-center">
                    <p class="text-primary text-xs font-bold uppercase mb-2">NativePHP Pro</p>
                    <p class="text-slate-400 text-[10px]">Build natively, code simply.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            lucide.createIcons();
            
            Alpine.data('bookApp', (serverBooks) => ({
                books: serverBooks,
                isModalOpen: false,
                isMenuOpen: false,
                selectedBook: null,

                getCover(book, zoom = 'M') {
                    if (!book?.volumeInfo?.imageLinks) return 'https://via.placeholder.com/300x450?text=No+Cover';
                    const link = book.volumeInfo.imageLinks.thumbnail || book.volumeInfo.imageLinks.smallThumbnail;
                    return link.replace('http:', 'https:').replace('&zoom=1', `&zoom=${zoom === 'L' ? '3' : '1'}`);
                },

                getAuthor(book) {
                    return book?.volumeInfo?.authors?.join(', ') || 'Unknown Author';
                },

                openInfo(book) {
                    this.selectedBook = book;
                    this.isModalOpen = true;
                }
            }));
        });
    </script>
</body>
</html>

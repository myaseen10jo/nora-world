@auth
<nav x-data="searchComponent()" x-init="init()" @click.away="close()" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-stone-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            {{-- Logo --}}
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/nora/logo.jpeg') }}" alt="NORA" class="h-8 w-auto object-contain">
                </a>
            </div>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('home') ? 'text-stone-900' : '' }}">
                    Home
                </a>
                <a href="{{ route('nora.gallery') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('nora.gallery') ? 'text-stone-900' : '' }}">
                    Gallery
                </a>
                <a href="{{ route('products.index') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('products.*') ? 'text-stone-900' : '' }}">
                    Shop
                </a>
                <a href="{{ route('collections.index') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('collections.*') ? 'text-stone-900' : '' }}">
                    Collections
                </a>
                <a href="{{ route('nora.about') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('nora.about') ? 'text-stone-900' : '' }}">
                    Our Story
                </a>
            </div>

            {{-- Search --}}
            <div class="hidden md:flex flex-1 max-w-sm mx-8">
                <div class="relative w-full" @click.away="close()">
                    <input
                        type="search"
                        x-model="query"
                        x-on:input.debounce.300ms="search()"
                        x-on:focus="if (query.length >= 2) open = true"
                        x-on:keydown.escape="close()"
                        x-on:keydown.arrow-down.prevent="highlightNext()"
                        x-on:keydown.arrow-up.prevent="highlightPrev()"
                        x-on:keydown.enter.prevent="goToHighlighted()"
                        placeholder="Search..."
                        class="w-full pl-10 pr-4 py-2 bg-stone-50 border-0 rounded-full text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-200 focus:bg-white transition-all duration-200"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div x-show="loading" x-transition class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <button x-show="query.length > 0" x-on:click="clearSearch()" x-transition class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-stone-600">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Search Results Dropdown --}}
                    <div x-show="open && (results.length > 0 || query.length >= 2)" x-transition class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-stone-100 overflow-hidden z-50" style="display: none;">
                        <template x-if="results.length > 0">
                            <div>
                                <div class="px-4 py-2 border-b border-stone-50">
                                    <p class="text-[11px] font-medium text-stone-400 uppercase tracking-wider">Products</p>
                                </div>
                                <ul class="max-h-72 overflow-y-auto">
                                    <template x-for="(product, index) in results" :key="product.id">
                                        <li>
                                            <a :href="product.url" class="flex items-center gap-3 px-4 py-3 hover:bg-stone-50 transition-colors" :class="{ 'bg-stone-50': index === highlightedIndex }" @mouseenter="highlightedIndex = index">
                                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-stone-100 flex-shrink-0">
                                                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" loading="lazy">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-medium text-stone-800 truncate" x-text="product.name"></h4>
                                                    <span class="text-xs text-stone-500" x-text="product.price"></span>
                                                </div>
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                                <div class="px-4 py-2 border-t border-stone-50 bg-stone-50/50">
                                    <a :href="'{{ route('products.index') }}?search=' + encodeURIComponent(query)" class="text-xs font-medium text-stone-500 hover:text-stone-700">
                                        View all results →
                                    </a>
                                </div>
                            </div>
                        </template>
                        <template x-if="results.length === 0 && query.length >= 2 && !loading">
                            <div class="px-6 py-8 text-center">
                                <p class="text-sm text-stone-400">No products found</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('orders.index') }}" class="text-[13px] font-medium text-stone-500 hover:text-stone-900 transition-colors">
                    Orders
                </a>

                <div class="w-px h-4 bg-stone-200"></div>

                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-[13px] font-medium text-stone-700 hover:text-stone-900 transition-colors">
                        <div class="w-7 h-7 bg-stone-900 rounded-full flex items-center justify-center text-white text-[11px] font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="hidden lg:inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
                        <svg class="h-3 w-3 text-stone-400" fill="none" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-stone-100 py-1 z-50" style="display: none;">
                        <div class="px-4 py-2 border-b border-stone-50">
                            <p class="text-sm font-medium text-stone-800">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-stone-400">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-stone-600 hover:bg-stone-50">Profile</a>
                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-stone-600 hover:bg-stone-50">My Orders</a>
                        <div class="border-t border-stone-50 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu Toggle --}}
            <div class="md:hidden flex items-center gap-2">
                <button @click="mobileSearchOpen = !mobileSearchOpen" class="p-2 text-stone-500 hover:text-stone-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-stone-500 hover:text-stone-700">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Search --}}
    <div x-show="mobileSearchOpen" x-transition class="md:hidden border-t border-stone-100" style="display: none;">
        <div class="p-4">
            <div class="relative">
                <input type="search" x-model="query" x-on:input.debounce.300ms="search()" placeholder="Search..." class="w-full pl-10 pr-4 py-2.5 bg-stone-50 border-0 rounded-lg text-sm focus:ring-2 focus:ring-stone-200">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
            <div x-show="results.length > 0" class="mt-2 bg-white rounded-lg shadow-lg border border-stone-100 overflow-hidden">
                <ul class="divide-y divide-stone-50 max-h-64 overflow-y-auto">
                    <template x-for="product in results" :key="product.id">
                        <li>
                            <a :href="product.url" class="flex items-center gap-3 px-3 py-2.5 hover:bg-stone-50">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-stone-100 flex-shrink-0">
                                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" loading="lazy">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-stone-800 truncate" x-text="product.name"></h4>
                                    <span class="text-xs text-stone-500" x-text="product.price"></span>
                                </div>
                            </a>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150" class="md:hidden border-t border-stone-100 bg-white" style="display: none;">
        <div class="py-3 px-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg {{ request()->routeIs('home') ? 'bg-stone-50' : '' }}">Home</a>
            <a href="{{ route('nora.gallery') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg {{ request()->routeIs('nora.gallery') ? 'bg-stone-50' : '' }}">Gallery</a>
            <a href="{{ route('products.index') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg {{ request()->routeIs('products.*') ? 'bg-stone-50' : '' }}">Shop</a>
            <a href="{{ route('collections.index') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg {{ request()->routeIs('collections.*') ? 'bg-stone-50' : '' }}">Collections</a>
            <a href="{{ route('nora.about') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg {{ request()->routeIs('nora.about') ? 'bg-stone-50' : '' }}">Our Story</a>
            <a href="{{ route('orders.index') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg {{ request()->routeIs('orders.*') ? 'bg-stone-50' : '' }}">My Orders</a>
        </div>
        <div class="border-t border-stone-100 px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-stone-900 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div>
                    <div class="text-sm font-medium text-stone-800">{{ Auth::user()->name }}</div>
                    <div class="text-[11px] text-stone-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-stone-600 hover:bg-stone-50 rounded-lg">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
@else
<nav x-data="searchComponent()" x-init="init()" @click.away="close()" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-stone-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/nora/logo.jpeg') }}" alt="NORA" class="h-8 w-auto object-contain">
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('home') ? 'text-stone-900' : '' }}">Home</a>
                <a href="{{ route('nora.gallery') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('nora.gallery') ? 'text-stone-900' : '' }}">Gallery</a>
                <a href="{{ route('products.index') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('products.*') ? 'text-stone-900' : '' }}">Shop</a>
                <a href="{{ route('collections.index') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('collections.*') ? 'text-stone-900' : '' }}">Collections</a>
                <a href="{{ route('nora.about') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors tracking-wide {{ request()->routeIs('nora.about') ? 'text-stone-900' : '' }}">Our Story</a>
            </div>

            <div class="hidden md:flex flex-1 max-w-sm mx-8">
                <div class="relative w-full" @click.away="close()">
                    <input type="search" x-model="query" x-on:input.debounce.300ms="search()" x-on:focus="if (query.length >= 2) open = true" x-on:keydown.escape="close()" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-stone-50 border-0 rounded-full text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-200 focus:bg-white transition-all duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <div x-show="open && (results.length > 0 || query.length >= 2)" x-transition class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-stone-100 overflow-hidden z-50" style="display: none;">
                        <template x-if="results.length > 0">
                            <div>
                                <ul class="max-h-72 overflow-y-auto">
                                    <template x-for="product in results" :key="product.id">
                                        <li>
                                            <a :href="product.url" class="flex items-center gap-3 px-4 py-3 hover:bg-stone-50 transition-colors">
                                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-stone-100 flex-shrink-0">
                                                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" loading="lazy">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-medium text-stone-800 truncate" x-text="product.name"></h4>
                                                    <span class="text-xs text-stone-500" x-text="product.price"></span>
                                                </div>
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                        <template x-if="results.length === 0 && query.length >= 2 && !loading">
                            <div class="px-6 py-8 text-center"><p class="text-sm text-stone-400">No products found</p></div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-[13px] font-medium text-stone-600 hover:text-stone-900 transition-colors px-3 py-2 hidden sm:inline-flex">Login</a>
                <a href="{{ route('register') }}" class="text-[13px] font-medium bg-stone-900 text-white px-5 py-2 rounded-full hover:bg-stone-800 transition-colors hidden sm:inline-flex">Register</a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-stone-500 hover:text-stone-700">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-stone-100 bg-white" style="display: none;">
        <div class="py-3 px-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg">Home</a>
            <a href="{{ route('nora.gallery') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg">Gallery</a>
            <a href="{{ route('products.index') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg">Shop</a>
            <a href="{{ route('collections.index') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg">Collections</a>
            <a href="{{ route('nora.about') }}" class="block px-3 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50 rounded-lg">Our Story</a>
        </div>
        <div class="border-t border-stone-100 px-4 py-3 flex gap-2">
            <a href="{{ route('login') }}" class="flex-1 text-center text-sm font-medium text-stone-700 py-2.5 rounded-lg border border-stone-200 hover:bg-stone-50">Login</a>
            <a href="{{ route('register') }}" class="flex-1 text-center text-sm font-medium text-white py-2.5 rounded-lg bg-stone-900 hover:bg-stone-800">Register</a>
        </div>
    </div>
</nav>
@endauth

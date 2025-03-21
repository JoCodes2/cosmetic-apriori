<header class="bg-pink-400 shadow" x-data="{ open: false }">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}">
                <img src="{{ asset('Image/maxie.png') }}" alt="Maxie Skincare Logo" class="h-10">
            </a>
            <span class="text-white text-lg font-bold">MaxieSkincare</span>
        </div>

        <!-- Desktop Navbar -->
        <div class="hidden md:flex space-x-6">
            <a href="{{ url('/') }}"
               class="py-1 border-b-2 text-white {{ request()->is('/') ? 'border-white' : 'border-transparent text-white' }}">
                Beranda
            </a>
            <a href="{{ url('/product-web') }}"
               class="py-1 border-b-2 text-white {{ request()->is('product-web') ? 'border-white' : 'border-transparent text-white' }}">
                Produk
            </a>
            <a href="{{ url('/chart') }}"
               class="relative py-1 border-b-2 text-white {{ request()->is('chart') ? 'border-white' : 'border-transparent text-white' }}">
                <i class="fas fa-shopping-cart mr-2 pt-1"></i>
                <span id="cart-count-navbar" class="absolute -top-2 -right-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full hidden">0</span>
            </a>
        </div>

        <!-- Hamburger Menu (Mobile) -->
        <div class="md:hidden">
            <button @click="open = !open" class="text-white focus:outline-none">
                <i class="fas fa-bars text-2xl"></i> <!-- Ikon menu titik tiga -->
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="open" class="md:hidden bg-pink-400 text-white py-4 space-y-2">
        <a href="{{ url('/') }}" class="block py-1 border-b-2 text-white{{ request()->is('/') ? 'border-white' : 'border-transparent text-white' }}">Beranda</a>
        <a href="{{ url('/product-web') }}" class="block py-1 border-b-2 text-white {{ request()->is('product-web') ? 'border-white' : 'border-transparent text-white' }}">Produk</a>
        <a href="{{ url('/chart') }}" class="block py-1 border-b-2 text-white {{ request()->is('chart') ? 'border-white' : 'border-transparent text-white' }}">Keranjang</a>
    </div>
</header>

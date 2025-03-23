<header class="bg-pink-400 shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}">
                <img src="{{ asset('Image/maxie.png') }}" alt="Maxie Skincare Logo" class="h-10">
            </a>
            <span class="text-white text-lg font-bold">MaxieSkincare</span>
        </div>

        <!-- Desktop Navbar + Search -->
        <div class="hidden md:flex space-x-6 relative items-center">
            <!-- Form Search -->
            <div class="relative w-64">
                <input type="text" id="search-box" class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Search...">

                <!-- Tombol Hapus Input -->
                <button type="button" id="clear-search" class="absolute right-3 top-2 text-gray-500 hover:text-gray-700 hidden">✖</button>

                <!-- Search Results Dropdown -->
                <div id="search-results" class="absolute mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg z-50 hidden">
                    <ul id="search-list"></ul>
                </div>
            </div>

            <a href="{{ url('/') }}" class="text-white">Beranda</a>
            <a href="{{ url('/product-web') }}" class="text-white">Produk</a>
            <a href="{{ url('/chart') }}" class="relative text-white">
                <i class="fas fa-shopping-cart mr-2"></i>
                <span id="cart-count-navbar" class="absolute -top-2 -right-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full hidden">0</span>
            </a>
        </div>

        <!-- Hamburger Menu (Mobile) -->
        <div class="md:hidden">
            <button id="menu-toggle" class="text-white focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-pink-400 text-white py-4 space-y-2">
        <a href="{{ url('/') }}" class="block py-1 text-white">Beranda</a>
        <a href="{{ url('/product-web') }}" class="block py-1 text-white">Produk</a>
        <a href="{{ url('/chart') }}" class="block py-1 text-white">Keranjang</a>

        <!-- Form Search (Mobile) -->
        <div class="w-full px-4 mt-2 relative">
            <input type="text" id="mobile-search-box" class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Search...">

            <!-- Tombol Hapus Input -->
            <button type="button" id="mobile-clear-search" class="absolute right-3 top-2 text-gray-500 hover:text-gray-700 hidden">✖</button>

            <!-- Search Results Dropdown for Mobile -->
            <div id="mobile-search-results" class="absolute w-full bg-white border border-gray-300 rounded-md shadow-lg z-50 mt-2 hidden">
                <ul id="mobile-search-list"></ul>
            </div>
        </div>
    </div>
</header>

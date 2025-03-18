<header class="bg-pink-400 shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-xl text-white font-bold"><a href="{{ url('/h') }}">OIShop</a></div>
        <div class="flex space-x-4">
            <a class="text-white" href="{{ url('/product-web') }}">Produk</a>
            <a class="text-white relative" href="{{ url('/chart') }}">
                <i class="fas fa-shopping-cart mr-2 pt-1"></i>
                <span id="cart-count-navbar" class="absolute -top-2 -right-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full hidden">0</span>
            </a>
        </div>
    </div>
</header>
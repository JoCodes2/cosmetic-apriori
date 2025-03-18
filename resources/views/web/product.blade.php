@extends('ui.base')

@section('title')
    Product
@endsection

@section('content')
    <div class="mb-4">
        <a class="text-gray-500 flex items-center space-x-1" href="{{ url('/') }}">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">Semua Produk</h1>
    <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $.ajax({
            url: "v1/product",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.code === 200) {
                    let products = response.data;
                    let productContainer = $("#product-list");
                    productContainer.empty();

                    $.each(products, function(index, product) {
                        let productCard = `
                            <div class="bg-white shadow rounded-lg overflow-hidden p-4">
                                <img class="w-full h-48 object-cover" src="https://placehold.co/200" alt="${product.name}">
                                <div class="p-4">
                                    <h2 class="text-lg font-semibold">${product.name}</h2>
                                    <p class="text-gray-600">Rp ${product.price.toLocaleString()}</p>
                                    <button class="add-to-cart mt-4 w-full bg-pink-400 border border-gray-300 text-white py-2 rounded-lg flex items-center justify-center space-x-2"
                                        data-id="${product.id}"
                                        data-name="${product.name}"
                                        data-price="${product.price}">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Tambahkan ke Keranjang</span>
                                    </button>
                                </div>
                            </div>
                        `;
                        productContainer.append(productCard);
                    });
                } else {
                    console.error("Gagal mengambil data produk");
                }
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan:", error);
            }
        });

        function updateCartCountNavbar() {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            let cartCountElement = $("#cart-count-navbar");

            if (cartCountElement.length) {
                if (totalItems > 0) {
                    cartCountElement.text(totalItems).removeClass("hidden");
                } else {
                    cartCountElement.text("0").addClass("hidden");
                }
            }
        }

        updateCartCountNavbar();

        window.addEventListener("storage", function(event) {
            if (event.key === "cart") {
                updateCartCountNavbar();
            }
        });

        $(document).on("click", ".add-to-cart", function() {
            let productId = $(this).data("id");
            let productName = $(this).data("name");
            let productPrice = $(this).data("price");

            addToCart(productId, productName, productPrice);
            updateCartCountNavbar();
            showAlert("Produk berhasil ditambahkan ke keranjang!");
        });

        function addToCart(id, name, price) {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            let existingProduct = cart.find(item => item.id_product === id);

            if (existingProduct) {
                existingProduct.qty += 1;
                existingProduct.total_price = existingProduct.qty * existingProduct.price_product;
            } else {
                cart.push({
                    id_product: id,
                    name_product: name,
                    price_product: price,
                    qty: 1,
                    total_price: price
                });
            }

            localStorage.setItem("cart", JSON.stringify(cart));
            window.dispatchEvent(new Event("storage"));
        }

        function showAlert(message) {
            let alertContainer = $("#alert-container");
            let alertId = "alert-" + new Date().getTime();
            let alertElement = `
                <div id="${alertId}" class="bg-white text-gray-800 px-4 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-opacity duration-500 opacity-100">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>${message}</span>
                </div>
            `;

            alertContainer.append(alertElement);

            setTimeout(() => {
                $("#" + alertId).fadeOut(500, function() {
                    $(this).remove();
                });
            }, 2000);
        }
    });
</script>

@endsection

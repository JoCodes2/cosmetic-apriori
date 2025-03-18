@extends('ui.base')
@section('title')
    Keranjang Anda
@endsection
@section('content')
    <a href="{{ url('/product-web') }}" class="text-gray-500 mb-4 inline-block">&lt; Kembali</a>
    <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

    <div class="flex flex-col lg:flex-row lg:space-x-8">
        <!-- Tabel Produk -->
        <div class="w-full lg:w-2/3 mb-8 lg:mb-0">
            <table class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left">Produk</th>
                        <th class="py-3 px-4 text-left">Harga</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Total</th>
                        <th class="py-3 px-4"></th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    <!-- Data dari localStorage akan dimasukkan di sini -->
                </tbody>
            </table>
        </div>

        <!-- Form & Ringkasan Pesanan -->
        <div class="w-full lg:w-1/3">
            <!-- Form Pesanan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Informasi Pemesanan</h2>
                <div class="mb-2">
                    <label class="block text-gray-700">Nama</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md transition duration-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-400 hover:border-pink-400">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700">Nomor HP</label>
                    <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md transition duration-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-400 hover:border-pink-400">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Alamat</label>
                    <textarea id="address" name="address" class="w-full px-3 py-2 border border-gray-300 rounded-md transition duration-300 focus:border-pink-400 focus:ring-1 focus:ring-pink-400 hover:border-pink-400"></textarea>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>
                <div class="flex justify-between mb-2">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Pengiriman</span>
                    <span>Gratis</span>
                </div>
                <div class="flex justify-between font-bold text-lg mb-4">
                    <span>Total</span>
                    <span id="total-price">Rp 0</span>
                </div>
                <button id="order-btn" class="w-full bg-pink-400 text-white py-2 rounded hover:bg-pink-500 transition duration-300">Pesan Sekarang</button>
            </div>
        </div>
    </div>
@endsection



@section('script')
<script>
$(document).ready(function () {
    loadCart();

    function loadCart() {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        let cartBody = $("#cart-body");
        let subtotal = 0;

        cartBody.empty();

        if (cart.length === 0) {
            cartBody.append(`
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Belum ada produk di keranjang Anda</td>
                </tr>
            `);
        } else {
            $.each(cart, function (index, item) {
                let price = parseFloat(item.price_product) || 0;
                let quantity = parseInt(item.qty) || 1;
                let totalItemPrice = price * quantity;
                subtotal += totalItemPrice;

                let row = `
                    <tr class="border-t">
                        <td class="py-3 px-4">${item.name_product || "Produk Tidak Diketahui"}</td>
                        <td class="py-3 px-4">Rp ${price.toLocaleString()}</td>
                        <td class="py-3 px-4 flex items-center">
                            <button class="px-2 py-1 bg-gray-200 rounded update-qty" data-index="${index}" data-type="decrease">-</button>
                            <span class="mx-2">${quantity}</span>
                            <button class="px-2 py-1 bg-gray-200 rounded update-qty" data-index="${index}" data-type="increase">+</button>
                        </td>
                        <td class="py-3 px-4">Rp ${totalItemPrice.toLocaleString()}</td>
                        <td class="py-3 px-4 text-red-500">
                            <i class="fas fa-trash-alt remove-item cursor-pointer" data-index="${index}"></i>
                        </td>
                    </tr>
                `;

                cartBody.append(row);
            });
        }

        $("#subtotal").text(`Rp ${subtotal.toLocaleString()}`);
        $("#total-price").text(`Rp ${subtotal.toLocaleString()}`);
    }

    function updateCart(index, type) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        if (type === "increase") {
            cart[index].qty++;
        } else if (type === "decrease" && cart[index].qty > 1) {
            cart[index].qty--;
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        loadCart();
    }

    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        cart.splice(index, 1);
        localStorage.setItem("cart", JSON.stringify(cart));
        loadCart();
    }

    $(document).on("click", ".update-qty", function () {
        let index = $(this).data("index");
        let type = $(this).data("type");
        updateCart(index, type);
    });

    $(document).on("click", ".remove-item", function () {
        let index = $(this).data("index");
        removeItem(index);
    });

    $("#order-btn").click(function () {
        let name = $("#name").val().trim();
        let phone = $("#phone").val().trim();
        let address = $("#address").val().trim();
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        if (!name || !phone || !address) {
            showAlert("Semua field harus diisi!", "error");
            return;
        }

        Swal.fire({
            title: "Yakin dengan pesanan Anda?",
            text: "Pesanan akan diproses setelah dikonfirmasi",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, pesan sekarang!",
            cancelButtonText: "Batal",
            confirmButtonColor: "#6861CE",
        }).then((result) => {
            if (result.isConfirmed) {
                let orderData = {
                    customer_name: name,
                    phone: phone.startsWith("+") ? phone : "+62" + phone,
                    address: address,
                    orders: cart.map((item) => ({
                        id_product: item.id_product,
                        name_product: item.name_product,
                        qty: item.qty,
                        price_product: item.price_product,
                        total_price: item.qty * item.price_product,
                    })),
                };

                $.ajax({
                    url: "v1/order/create",
                    method: "POST",
                    data: JSON.stringify(orderData),
                    contentType: "application/json",
                    success: function (response) {
                        console.log(removeItem);

                        if (response.code === 200) {
                            localStorage.removeItem("cart");
                            showAlert("Pesanan berhasil dibuat!", "success");
                            loadCart();
                        } else {
                            showAlert("Terjadi kesalahan, coba lagi!", "error");
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        showAlert("Gagal mengirim pesanan!", "error");
                    },
                });
            }
        });
    });

    function showAlert(message, type) {
        let color = type === "success" ? "green" : "red";
        $("#order-btn").after(
            `<p class="text-${color}-500 mt-2 text-sm">${message}</p>`
        );
        setTimeout(() => $(".text-" + color + "-500").remove(), 3000);
    }
});

</script>
@endsection

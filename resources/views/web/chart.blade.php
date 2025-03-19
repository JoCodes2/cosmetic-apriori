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

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl w-full">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold">Faktur</h1>
                <p class="text-gray-500">INV-20250319-102832-00001</p>
            </div>
            <div class="text-right">
                <span class="bg-yellow-200 text-yellow-800 text-sm font-semibold px-2 py-1 rounded">Tertunda</span>
                <p class="text-gray-500">Tanggal Jatuh Tempo: <span class="font-semibold text-black">19 March 2025</span></p>
            </div>
        </div>
        <div class="flex justify-between mb-6">
            <div>
                <h2 class="text-gray-500 text-sm font-semibold">TAGIHAN UNTUK</h2>
                <p class="text-lg font-semibold">Keiko Adkins</p>
                <p class="text-gray-500">Cumque est amet per</p>
                <p class="text-gray-500">878787767887</p>
            </div>
            <div class="text-right">
                <h2 class="text-gray-500 text-sm font-semibold">INFORMASI FAKTUR</h2>
                <p class="text-gray-500">Tanggal Dibuat: <span class="font-semibold text-black">19 March 2025</span></p>
                <p class="text-gray-500">ID Faktur: <span class="font-semibold text-black">9e7f72c3</span></p>
            </div>
        </div>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full bg-gray-50 border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Item</th>
                        <th class="py-2 px-4 border-b">Harga Satuan</th>
                        <th class="py-2 px-4 border-b">Jumlah</th>
                        <th class="py-2 px-4 border-b">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">Isabella Allison</td>
                        <td class="py-2 px-4 border-b">Rp 957</td>
                        <td class="py-2 px-4 border-b">2</td>
                        <td class="py-2 px-4 border-b">Rp 1.914</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b font-semibold" colspan="3">Total</td>
                        <td class="py-2 px-4 border-b font-semibold">Rp 1.914</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-between items-center mb-6">
            <button class="bg-gray-200 text-black px-4 py-2 rounded flex items-center">
                <i class="fas fa-download mr-2"></i> Unduh
            </button>
        </div>
        <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <div>
                    <p class="font-semibold">Terima kasih atas pembelian Anda</p>
                    <p>Jika Anda memiliki pertanyaan mengenai faktur ini, silakan hubungi layanan pelanggan kami.</p>
                </div>
            </div>
        </div>
    </div>
        <!-- Form & Ringkasan Pesanan -->
        <div class="w-full lg:w-1/3">
            <!-- Form Pesanan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Informasi Pemesanan</h2>
                <form id="orderForm" method="POST">
                    <input type="hidden" id="id" name="id" >
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
                </form>
            </div>
            <div id="alert-container"></div>
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
    $("#phone").on("input", function () {
        this.value = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
    });

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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


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

    function checkCart() {
        let orders = JSON.parse(localStorage.getItem("cart")) || [];
        if (orders.length === 0) {
            $("#order-btn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
        } else {
            $("#order-btn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
        }
    }

    // Panggil saat halaman dimuat
    $(document).ready(function () {
        checkCart();
    });

    // Pantau perubahan localStorage (misalnya saat user menambah/menghapus item di cart)
    window.addEventListener("storage", checkCart);

    function validation() {
        $('#orderForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                address: {
                    required: true,
                    minlength: 5,
                    maxlength: 500
                }
            },
            messages: {
                name: {
                    required: "Nama tidak boleh kosong.",
                    minlength: "Nama minimal 3 karakter.",
                    maxlength: "Nama tidak boleh lebih dari 255 karakter."
                },
                phone: {
                    required: "Nomor HP wajib diisi.",
                    digits: "Nomor HP harus berupa angka.",
                    minlength: "Nomor HP minimal 10 digit.",
                    maxlength: "Nomor HP maksimal 15 digit."
                },
                address: {
                    required: "Alamat tidak boleh kosong.",
                    minlength: "Alamat minimal 5 karakter.",
                    maxlength: "Alamat tidak boleh lebih dari 500 karakter."
                }
            },
            highlight: function (element) {
                $(element).removeClass('border-gray-300').addClass('border-red-500');
            },
            unhighlight: function (element) {
                $(element).removeClass('border-red-500').addClass('border-green-500');
            },
            errorPlacement: function (error, element) {
                error.addClass('text-red-500 text-sm');
                error.insertAfter(element);
            }
        });
    }

    validation();

    $('#name, #phone, #address').on('input', function () {
        $(this).valid();
    });

    $("#order-btn").click(function () {
        if (!$('#orderForm').valid()) {
            return;
        }

        Swal.fire({
            title: '<span style="font-size: 22px"> Konfirmasi!</span>',
            html: "Apakah anda yakin ingin memesan?",
            showCancelButton: true,
            showConfirmButton: true,
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya',
            reverseButtons: true,
            confirmButtonColor: 'rgb(244 114 182 )',
            cancelButtonColor: '#EFEFEF',
            customClass: {
                cancelButton: 'text-dark'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                processOrder();
            }
        });
    });

    function processOrder() {
        let id = $("#id").val();
        let name = $("#name").val();
        let phone = $("#phone").val();
        let address = $("#address").val();
        let orders = JSON.parse(localStorage.getItem("cart")) || [];

        let simplifiedOrders = orders.map(order => ({
            id_product: order.id_product,
            name_product: order.name_product,
            qty: order.qty,
            price_product: order.price_product,
            total_price: order.qty * order.price_product,
        }));

        let formData = {
            id,
            name,
            phone,
            address,
            orders: simplifiedOrders
        };

        $("#order-btn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");

        $.ajax({
            url: "v1/order/create",
            method: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            success: function (response) {
                if (response.code === 200) {
                    localStorage.removeItem("cart");
                    showAlert("Pesanan berhasil dibuat!", "success");
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(response.message || "Terjadi kesalahan, coba lagi!", "error");
                }
            },
            error: function (xhr) {
                let errorMessage = "Gagal mengirim pesanan!";
                if (xhr.status === 422) {
                    let response = JSON.parse(xhr.responseText);
                    let errors = response.errors;
                    errorMessage = Object.values(errors).map(msg => `<li>${msg}</li>`).join("");
                    showAlert(`<ul>${errorMessage}</ul>`, "error");
                } else {
                    showAlert(errorMessage, "error");
                }
            },
            complete: function () {
                $("#order-btn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
            }
        });
    }

    function showAlert(message, type) {
        let bgColor = type === "success" ? "bg-green-500" : "bg-red-500";
        let textColor = "text-white";

        let alertHtml = `
            <div class="mt-2 p-2 rounded-lg ${bgColor} ${textColor} text-sm text-center">
                ${message}
            </div>
        `;

        $("#alert-container").html(alertHtml);

        setTimeout(() => $("#alert-container").html(""), 5000);
    }


});

</script>
@endsection

@extends('ui.base')
@section('title')
    Keranjang Anda
@endsection
@section('content')
    <div class="mb-4">
        <a class="text-gray-500 flex items-center space-x-1" href="{{ url('/product-web') }}">
            <i class="fas fa-arrow-left"></i>
            <span>Produk</span>
        </a>
    </div>
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
        <div id="orderSummary" class="w-full lg:w-1/3">
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
        <div id="invoiceContainer" class="hidden"></div>
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
                console.log(response);

                if (response.code === 200) {
                    localStorage.removeItem("cart");
                    showTailwindAlert("Pesanan berhasil dikirim! Memuat invoice...", "success");

                    let billingId = response.data.id || response.data.billing.id;

                    localStorage.setItem("lastBillingId", billingId);

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showTailwindAlert(response.message || "Terjadi kesalahan, coba lagi!", "error");
                }
            },
            error: function () {
                showTailwindAlert("Gagal mengirim pesanan!", "error");
            },
            complete: function () {
                $("#order-btn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
            }
        });
    }
    let lastBillingId = localStorage.getItem("lastBillingId");

    if (lastBillingId) {
        $("#orderSummary").hide();
        fetchInvoice(lastBillingId);
        localStorage.removeItem("lastBillingId");
    }

    function fetchInvoice(id) {
        $.ajax({
            url: `v1/order/get/${id}`,
            method: "GET",
            success: function (response) {
                if (response.code === 200) {
                    showInvoiceModal(response.data);
                } else {
                    showTailwindAlert("Gagal mengambil data invoice", "error");
                }
            },
            error: function () {
                showTailwindAlert("Terjadi kesalahan saat mengambil data invoice", "error");
            }
        });
    }

    function showInvoiceModal(data) {
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        function status(status_transaction) {
            if (status_transaction === "paid") {
                return `<span class='bg-green-200 text-green-800 text-sm font-semibold px-2 py-1 rounded'>Lunas</span>`;
            } else if (status_transaction === "unpaid") {
                return `<span class='bg-yellow-200 text-yellow-800 text-sm font-semibold px-2 py-1 rounded'>Belum Lunas</span>`;
            } else {
                return `<span class='bg-red-200 text-red-800 text-sm font-semibold px-2 py-1 rounded'>Dibatalkan</span>`;
            }
        }

        let invoiceHtml = `
            <div id='fakturInvoice' class='rounded-lg shadow-lg p-8 max-w-2xl w-full bg-white border bg-white'>
                <div class='flex justify-between mb-6'>
                    <div>
                        <h2 class='text-gray-500 text-sm font-semibold'>TAGIHAN UNTUK</h2>
                        <p class='text-gray-500'><span class='font-semibold text-black'>${data.customer.name}</span></p>
                        <p class='text-gray-500'><span class='font-semibold text-black'>${data.customer.phone}</span></p>
                        <span class='text-gray-500 text-sm font-semibold rounded'>${data.customer.address}</span>
                    </div>
                    <div class='text-right'>
                        <h2 class='text-gray-500 text-sm font-semibold'>INFORMASI FAKTUR</h2>
                        <p class='text-gray-500'><span class='font-semibold text-black'>${data.payment_date}</span></p>
                        <p class='text-gray-500'><span class='font-semibold text-black'>${data.code_transaction}</span></p>
                        ${status(data.status_transaction)}
                    </div>
                </div>
                <div class='mb-6'>
                    <table class='min-w-full bg-gray-50 border border-gray-200'>
                        <thead>
                            <tr>
                                <th class='py-2 px-4 border-b'>Item</th>
                                <th class='py-2 px-4 border-b'>Harga Satuan</th>
                                <th class='py-2 px-4 border-b'>Jumlah</th>
                                <th class='py-2 px-4 border-b'>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.billing_items.map(item => `
                                <tr>
                                    <td class='py-2 px-4 border-b'>${item.name_product}</td>
                                    <td class='py-2 px-4 border-b'>Rp ${formatRupiah(item.price_product)}</td>
                                    <td class='py-2 px-4 border-b'>${item.qty}</td>
                                    <td class='py-2 px-4 border-b'>Rp ${formatRupiah(item.total_price)}</td>
                                </tr>
                            `).join('')}
                            <tr>
                                <td class='py-2 px-4 border-b font-semibold' colspan='3'>Total</td>
                                <td class='py-2 px-4 border-b font-semibold'>Rp ${formatRupiah(data.total_payment)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <button id="downloadInvoice" class="bg-gray-200 text-black px-4 py-2 rounded flex items-center">
                        <i class="fas fa-download mr-2"></i> Unduh
                    </button>
                </div>
                <div class="bg-pink-50 border border-pink-200 text-pink-700 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <div>
                            <p class="font-semibold">Terima kasih atas pembelian Anda</p>
                            <p>Jika Anda memiliki pertanyaan mengenai faktur ini, silakan hubungi layanan pelanggan kami.</p>
                        </div>
                    </div>
                </div>
            </div>`;

        $("#invoiceContainer").html(invoiceHtml).removeClass("hidden");

        // Event Listener untuk tombol download
        $("#downloadInvoice").on("click", function () {
            let element = document.getElementById('fakturInvoice');
            let customerName = data.customer.name.replace(/\s+/g, '_');
            let transactionCode = data.code_transaction;
            let fileName = `${customerName}-inv-${transactionCode}.pdf`;

            let options = {
                margin: 10,
                filename: fileName,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
               console.log("Nama File:", fileName);
               console.log("Element:", element);
            html2pdf().from(element).set(options).save().then(() => {
                setTimeout(() => {
                    location.reload();
                }, 2000);
            });
        });

    }

   function showTailwindAlert(message, type) {
        let iconClass = type === "success" ? "fas fa-check-circle text-white" : "fas fa-times-circle text-white";
        let bgColor = type === "success" ? "bg-green-500" : "bg-red-500";

        let alertDiv = $(`
            <div class="fixed top-5 right-5 px-4 py-3 rounded shadow-md text-white text-sm ${bgColor} flex items-center space-x-2">
                <i class="${iconClass}"></i>
                <span>${message}</span>
            </div>
        `);

        $("body").append(alertDiv);

        setTimeout(() => {
            alertDiv.fadeOut(500, () => alertDiv.remove());
        }, 3000);
    }


});

</script>
@endsection

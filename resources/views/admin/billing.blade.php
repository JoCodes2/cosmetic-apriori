@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-shopping-cart pr-2"></i> Data Pesanan</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pesanan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Nomor Telepon</th>
                                        <th>Total Pembayaran</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Barang</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="orderTable">
                                    {{-- Data dari AJAX akan masuk di sini --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Modal -->
        <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="invoiceModalLabel">Bukti Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="fakturInvoice">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h6 class="text-muted">TAGIHAN UNTUK</h6>
                                    <p><strong id="customerName"></strong></p>
                                    <p id="customerPhone"></p>
                                    <p id="customerAddress"></p>
                                </div>
                                <div class="text-end">
                                    <h6 class="text-muted">INFORMASI FAKTUR</h6>
                                    <p><strong id="paymentDate"></strong></p>
                                    <p><strong id="transactionCode"></strong></p>
                                    <span id="transactionStatus"></span>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceItems"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total</td>
                                        <td id="totalPayment" class="fw-bold"></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="alert alert-info">
                                <i class="fas fa-check-circle"></i> Terima kasih atas pembelian Anda. Jika ada pertanyaan,
                                hubungi layanan pelanggan.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            function getData() {
                $.ajax({
                    url: `/v1/order`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        let tableBody = "";
                        $.each(response.data, function(index, order) {
                            let statusBadge = (order.status_transaction === "paid") ?
                                "<span class='badge bg-success'>Lunas</span>" :
                                "<span class='badge bg-danger'>Belum Lunas</span>";

                            tableBody += "<tr>";
                            tableBody += "<td>" + (index + 1) + "</td>";
                            tableBody += "<td>" + order.code_transaction + "</td>";
                            tableBody += "<td>" + order.customer.name + "</td>";
                            tableBody += "<td>" + order.customer.phone + "</td>";
                            tableBody += "<td>Rp " + new Intl.NumberFormat("id-ID").format(order
                                .total_payment) + "</td>";
                            tableBody += "<td>" + order.payment_date + "</td>";
                            tableBody += "<td>" + statusBadge + "</td>";

                            tableBody += "<td><ul>";
                            $.each(order.billing_items, function(i, item) {
                                tableBody += "<li>" + item.name_product + " - Rp " +
                                    new Intl.NumberFormat("id-ID").format(item
                                        .total_price) +
                                    " (Qty: " + item.qty + ")</li>";
                            });
                            tableBody += "</ul></td>";

                            tableBody += `<td>
                        <button class="btn btn-sm btn-primary view-invoice" data-order-id="${order.id}">
                            <i class='fas fa-eye'></i> Lihat
                        </button>`;

                            if (order.status_transaction !== "paid") {
                                tableBody += `
                        <button class="btn btn-sm btn-success mark-paid" data-order-id="${order.id}">
                            <i class='fas fa-check'></i> Lunas
                        </button>
                        <button class="btn btn-sm btn-danger delete-order" data-order-id="${order.id}">
                            <i class='fas fa-trash'></i> Hapus
                        </button>`;
                            }
                            tableBody += `</td></tr>`;
                        });

                        $("#orderTable").html(tableBody);
                        $(".table").DataTable();
                    },
                    error: function() {
                        console.log("Gagal mengambil data dari server");
                    }
                });
            }

            getData();

            // ✅ Update Status Transaksi Saat Tombol "Lunas" Diklik
            $(document).on("click", ".mark-paid", function() {
                let orderId = $(this).data("order-id");

                $.ajax({
                    url: `/v1/order/${orderId}/status`,
                    method: "PUT",
                    data: {
                        status: "paid",
                        _token: "{{ csrf_token() }}"
                    }, // Kirim status baru
                    success: function(response) {
                        console.log(response);

                        Swal.fire({
                            title: "Berhasil!",
                            text: "Status transaksi berhasil diperbarui.",
                            icon: "success",
                            timer: 1500, // Pesan otomatis hilang dalam 1.5 detik
                            showConfirmButton: false
                        });

                        getData(); // Reload data setelah update
                    },
                    error: function() {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat memperbarui status.",
                            icon: "error"
                        });
                    }
                });
            });

            // Delete data button click handler
            $(document).on('click', '.delete-order', function() {
                let id = $(this).data('order-id');
                console.log(id);


                // Function to delete data
                function deleteDataById() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/v1/order/delete/${id}`,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.code === 200 || response.status === "success") {
                                successAlert('Data berhasil dihapus!');

                                // Tunggu sebentar sebelum reload
                                setTimeout(function() {
                                    location
                                        .reload(); // Reload browser setelah data terhapus
                                }, 1500);
                            } else {
                                errorAlert();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', xhr.responseText);
                            errorAlert();
                        }
                    });
                }

                // Show confirmation alert
                confirmAlert('Apakah Anda yakin ingin menghapus data?', deleteDataById);
            });

            // messeage alert
            // alert success message
            function successAlert(message) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1000,
                })
            }

            // alert error message
            function errorAlert() {
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan!',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1000,
                });
            }

            function reloadBrowsers() {
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }


            function confirmAlert(message, callback) {
                Swal.fire({
                    title: '<span style="font-size: 22px"> Konfirmasi!</span>',
                    html: message,
                    showCancelButton: true,
                    showConfirmButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya',
                    reverseButtons: true,
                    confirmButtonColor: '#48ABF7',
                    cancelButtonColor: '#EFEFEF',
                    customClass: {
                        cancelButton: 'text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        callback();
                    }
                });
            }

            // loading alert
            function loadingAllert() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }





            //invoice
            // Event saat tombol "Lihat" diklik
            $(document).on("click", ".view-invoice", function() {
                let orderId = $(this).data("order-id");

                $.ajax({
                    url: ` /v1/order/get/${orderId}`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);

                        if (response.code === 200) {
                            showInvoiceModal(response.data);
                        } else {
                            alert("Gagal mengambil data invoice.");
                        }
                    },
                    error: function() {
                        alert("Terjadi kesalahan saat mengambil data.");
                    }
                });
            });

            function showInvoiceModal(data) {
                // Menampilkan data pelanggan
                $("#customerName").text(data.customer.name);
                $("#customerPhone").text(data.customer.phone);
                $("#customerAddress").text(data.customer.address);
                $("#paymentDate").text(data.payment_date);
                $("#transactionCode").text(data.code_transaction);
                $("#transactionStatus").text(data.status_transaction);

                // Menampilkan daftar produk dalam transaksi
                let itemsHtml = data.billing_items.map(item => `
                    <tr>
                        <td>${item.name_product}</td>
                        <td>Rp ${new Intl.NumberFormat("id-ID").format(item.price_product)}</td>
                        <td>${item.qty}</td>
                        <td>Rp ${new Intl.NumberFormat("id-ID").format(item.total_price)}</td>
                    </tr>
                `).join('');

                $("#invoiceItems").html(itemsHtml);

                // Menampilkan total pembayaran
                $("#totalPayment").text(`Rp ${new Intl.NumberFormat("id-ID").format(data.total_payment)}`);

                // Menampilkan modal invoice
                let invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                invoiceModal.show();
            }


        });
    </script>



@endsection

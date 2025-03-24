@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-users pr-2"></i>Dashboard</h4>
        </div>

        <div class="row">
            <!-- Tabel Pesanan Hari Ini -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Pesanan Hari Ini</h4>
                    </div>
                    <div class="card-body">
                        <table id="todayOrdersTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama Pelanggan</th>
                                    <th>No Telepon</th>
                                    <th>Total Pembayaran</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat lewat AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            getTodayOrders();
        });

        function getTodayOrders() {
            console.log("🔄 Mengambil data pesanan hari ini...");

            $.ajax({
                url: "/v1/order/today", // Pastikan endpoint API benar
                method: "GET",
                dataType: "json",
                success: function(response) {
                    console.log("✅ Response dari server:", response);

                    if (!response.data || response.data.length === 0) {
                        console.warn("⚠️ Data kosong atau tidak ditemukan.");
                        $("#todayOrdersTable tbody").html(
                            "<tr><td colspan='7' class='text-center'>Tidak ada pesanan hari ini</td></tr>");
                        return;
                    }

                    let tableBody = "";
                    $.each(response.data, function(index, order) {
                        let statusBadge = (order.status_transaction === "paid") ?
                            "<span class='badge bg-success'>Lunas</span>" :
                            "<span class='badge bg-danger'>Belum Lunas</span>";

                        tableBody += `<tr>
                            <td>${index + 1}</td>
                            <td>${order.code_transaction}</td>
                            <td>${order.customer ? order.customer.name : "-"}</td>
                            <td>${order.customer ? order.customer.phone : "-"}</td>
                            <td>Rp ${new Intl.NumberFormat("id-ID").format(order.total_payment)}</td>
                            <td>${order.payment_date ?? "-"}</td>
                            <td>${statusBadge}</td>
                        </tr>`;
                    });

                    $("#todayOrdersTable tbody").html(tableBody);
                },
                error: function(xhr, status, error) {
                    console.error("❌ Gagal mengambil data dari server:", error);
                    console.log("🔍 Response dari server:", xhr.responseText);
                    $("#todayOrdersTable tbody").html(
                        "<tr><td colspan='7' class='text-center text-danger'>Terjadi kesalahan saat mengambil data.</td></tr>"
                    );
                }
            });
        }
    </script>
@endsection

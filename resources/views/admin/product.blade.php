@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="page-inner">
        <div class="page-header ">
            <h4 class="page-title"><i class="fas fa-users pr-2"></i>Data Produk</h4>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary " id="myBtn">
                                <i class="fas fa-plus pr-2"></i>Tambah Data
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="Product" class="display table table-striped table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Gambar Produk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="upsertDataModal" role="dialog" aria-labelledby="upsertDataModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl center" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="upsertDataModalLabel">
                            <i class="fas fa-tasks pr-2"></i> Form Data
                        </h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Image Preview -->
                        <div id="imagePreview" class="text-center mb-4"></div>

                        <!-- Form -->
                        <form id="upsertDataForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="id">

                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Name Input -->
                                    <div class="form-group">
                                        <label for="name">Nama Produk</label>
                                        <input type="text" class="form-control form-control-lg" name="name"
                                            id="name" placeholder="Masukkan Nama Produk">
                                        <small id="name-error" class="text-danger"></small>
                                    </div>

                                    <!-- Price Input -->
                                    <div class="form-group">
                                        <label for="price">Harga Barang</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control form-control-lg" name="price"
                                                id="price" placeholder="Masukkan Harga Barang">
                                        </div>
                                        <small id="price-error" class="text-danger"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" class="form-control-file" name="image" id="image">
                                        <small id="image-error" class="text-danger"></small>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary btn-lg" id="simpanData">Simpan</button>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            document.getElementById('price').addEventListener('input', function(e) {
                // Hanya izinkan angka (0-9)
                this.value = this.value.replace(/\D/g, '');
            });

            function getData() {
                $.ajax({
                    url: `/v1/product`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let tableBody = "";
                        $.each(response.data, function(index, item) {
                            tableBody += "<tr>";
                            tableBody += "<td>" + (index + 1) + "</td>";

                            tableBody += "<td>" + item.name + "</td>";
                            tableBody += "<td>Rp " + new Intl.NumberFormat("id-ID").format(item
                                .price) + "</td>";
                            tableBody += "<td><img src='/uploads/img-product/" + item
                                .image +
                                "' widht='100px' height='100px' alt='product Image'></td>";
                            tableBody += "<td>";
                            tableBody +=
                                "<button type='button' class='btn btn-outline-primary btn-sm edit-btn' data-id='" +
                                item.id + "'><i class='fas fa-edit'></i></button>";
                            tableBody +=
                                "<button type='button' class='btn btn-outline-danger btn-sm delete-confirm' data-id='" +
                                item.id + "'><i class='fas fa-trash'></i></button>";
                            tableBody += "</td>";
                            tableBody += "</tr>";
                        });

                        $("#Product tbody").html(tableBody);
                        $('#Product').DataTable({
                            destroy: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            order: []
                        });
                    },
                    error: function() {
                        console.log("Gagal mengambil data dari server");
                    }
                });
            }



            getData();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // create
            $(document).on('click', '#simpanData', function(e) {
                $('.text-danger').text('');
                e.preventDefault();

                let id = $('#id').val();
                let formData = new FormData($('#upsertDataForm')[0]);
                let url = id ? `/v1/product/update/${id}` : '/v1/product/create';
                let method = id ? 'POST' : 'POST';

                loadingAllert();

                $.ajax({
                    type: method,
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        Swal.close();

                        if (response.code === 422) { // Jika validasi gagal
                            let errors = response.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key + '-error').text(value[0]);
                            });
                        } else if (response.code === 200 || response.status === "success") {
                            successAlert('Data berhasil disimpan!');
                            reloadBrowsers();
                        } else {
                            errorAlert();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.close();
                        errorAlert();
                    }
                });
            });

            // Edit data button click handler
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/v1/product/get/${id}`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        $('#upsertDataModal').modal('show');

                        // Populate form fields with existing data
                        $('#id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#price').val(response.data.price);

                        // Display image preview
                        if (response.data.image) {
                            $('#imagePreview').html(
                                `<img src="/uploads/img-product/${response.data.image}" widht="200px" height="200px" alt="product Image">`
                            );
                        } else {
                            $('#imagePreview').html('<p>No image available</p>');
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data for edit:', error);
                    }
                });
            });

            // Delete data button click handler
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');

                // Function to delete data
                function deleteData() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/v1/product/delete/${id}`,
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
                confirmAlert('Apakah Anda yakin ingin menghapus data?', deleteData);
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

            // reset modal
            $('#upsertDataModal').on('hidden.bs.modal', function() {
                $('.text-danger').text('');
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
            });
            // event click btn create
            $(document).on('click', '#myBtn', function() {
                $('.text-danger').text('');
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
                $('#upsertDataModal').modal('show');
                $('#imagePreview').html('');
            })





        });
    </script>
@endsection

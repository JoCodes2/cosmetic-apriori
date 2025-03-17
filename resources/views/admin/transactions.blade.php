@extends('layouts.master')
@section('content')
<div class="page-inner">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h2 class="page-title">Transaksi Kasir</h2>
        <div class="ml-auto">
            <a class="btn btn-secondary ml-3" id="continueButton">Selesaikan Pembayaran
                <i class="fas fa-chevron-right ml-2 text-light"></i></a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <h2 class="mr-2 fw-bold">Total Pembayaran :</h2>
                            <h2 class="mr-2 fw-bold" id="sub_total_payment">Rp.</h2>
                        </div>
                        <button class="btn btn-primary ml-md-2" id="addListOrder" data-toggle="modal"
                            data-target="#listOrderModal">Tambah Pesanan
                            <i class="fas fa-plus fa-xl ml-2"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <h2 class="text-center fw-bold" id="emptyMessage">Belum ada pesanan</h2>
                    <div class="table-responsive" id="tableContainer">
                        <table id="dataListOrder" class="table  table-striped tb ">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Pesanan</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Detail Ukuran</th>
                                    <th scope="col">Kuantitas</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tBody" class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="listOrderModal"  role="dialog" aria-labelledby="listOrderTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered "  style="max-width: 70%;" role="document">
        <div class="modal-content">
            <div class="modal-header m-2">
                <h3 class="modal-title fw-bold" id="listOrderTitle">Tambah Pesanan</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="upsertListOrder">
                    <div class="row">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_product">Pesanan</label>
                                <select class="js-example-basic-single select2 validate-form" id="id_product" name="id_product" >
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="information">Keterangan</label>
                                <select class="form-control validate-form" id="information" name="information">
                                    <option value="" disabled selected>select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="defaultSize">
                            <div class="form-group">
                                <label for="detail_size">Detail Ukuran</label>
                                    <select class="form-control validate-form" id="detail_size" name="detail_size">
                                        <option value="" disabled selected>select</option>
                                    </select>
                            </div>
                        </div>
                        <input type="hidden" name="status_custom_product" id="status_custom_product" value="">
                        <div id="customSize" class="col-md-4">
                            <div class="form-group">
                                <label for="custom_size" class="mr-2">Detail Ukuran</label>
                                <div class="d-flex flex-row align-items-center">
                                    <input type="text" class="form-control validate-form " name="panjang" id="panjang" placeholder="0">
                                    <label for="x" class="px-2">X</label>
                                    <input type="text" class="form-control validate-form" name="lebar" id="lebar" placeholder="0">
                                    <label for="meter" class="pl-2">Meter</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="qty">Kuantitas</label>
                                <input type="number" class="form-control validate-form" id="qty" name="qty" placeholder="0" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="discount_total">Discount %</label>
                                <input type="text" class="form-control " id="discount_total" name="discount_total" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="note">Catatan</label>
                                <input type="text" class="form-control " id="note" name="note" placeholder="-">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit_price" id="hargaSatuanLabel">Harga Satuan</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="0" id="unit_price" name="unit_price"
                                         readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-primary" id="saveOrder">Tambah Pesanan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function() {
    function formatUnitPriceInput(input, formatForDisplay) {
        let value = input.value;
        let numericValue = value.replace(/[^0-9]/g, '');
        if (formatForDisplay) {
            input.value = numberWithCommas(numericValue);
        } else {
            input.value = numericValue;
        }
    }

    $('input[type="number"]').on('input', function() {
        if ($(this).val() < 0) {
            $(this).val(0);
        }
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function formatRupiah(angka) {
        if (typeof angka === 'number' && !isNaN(angka)) {
            if (angka !== 0) {
                let numberString = angka.toString();
                let split = numberString.split('.');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return 'Rp. ' + rupiah;
            } else {
                return 'Rp. 0';
            }
        } else {
            return 'Bukan angka';
        }
    }

    function populateProductOptions(products,placeholder, defaultValue) {
        let productNameSelect = $('#id_product');

        productNameSelect.select2({
            placeholder: 'Cari produk...',
            minimumInputLength: 3,
            ajax: {
                url: '/v1/product',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function (result, params) {
                    if (result && result.code === 200 && result.data && result.data.length > 0) {
                        const searchTerm = params.term.toLowerCase();
                        const filteredOptions = result.data.filter(item => {
                            const productName = item.product_name.toLowerCase();
                            return productName.includes(searchTerm);
                        }).slice(0, 10);

                        const options = filteredOptions.map(item => ({
                            id: item.id,
                            text: `${item.product_name} - ${item.category}`,
                            unit_product: item.unit_product,
                            category : item.category,
                        }));
                        productNameSelect.data('unit_product', options);

                        return {
                            results: options,
                        };
                    } else {
                        return {
                            results: [],
                        };
                    }
                },
                cache: true,
            },
            language: {
                inputTooShort: function (args) {
                    const remainingChars = 3 - args.input.length;
                    return 'Silahkan ketik minimal ' + remainingChars + ' karakter untuk pencarian.';
                },
                searching: function () {
                    return 'Mencari...';
                },
                noResults: function () {
                    return 'Data tidak ditemukan';
                },
            },
        });
        $('.js-example-basic-single').siblings('.select2-container').find('.select2-selection--single').addClass('form-control p-1');
        if (defaultValue) {
            productNameSelect.append(new Option(placeholder, defaultValue, true, true)).trigger('change');
        }
        return productNameSelect;

    }

    function populateInformationAndDetailSizeOptions(selectedProduct) {
        let informationSelect = $('#information');
        let detailSizeSelect = $('#detail_size');
        informationSelect.empty();
        detailSizeSelect.empty();

        $.each(selectedProduct.detail_product, function(index, detail) {
            let informationOption = $('<option>', {
                value: detail.id,
                text: detail.information
            });
            informationSelect.append(informationOption);
            informationOption.data("detail-size", detail.detail_size);
            informationOption.data("unit-price", detail.unit_price);

            let detailSizeOption = $('<option>', {
                value: detail.detail_size,
                text: detail.detail_size
            });
            detailSizeSelect.append(detailSizeOption);
            detailSizeOption.data("information", detail.id);
            detailSizeOption.data("unit-price", detail.unit_price);
        });
    }

    function setUnitPriceAndSize(selectedOption) {
        let unitPriceInput = $('#unit_price');
        let unitSizeSelect = $('#detail_size');

        let unitPrice = selectedOption.data("unit-price");
        let detailSize = selectedOption.data("detail-size");

        if (unitPrice !== undefined) {
            unitPriceInput.val(numberWithCommas(unitPrice));
        } else {
            unitPriceInput.val("");
        }

        if (detailSize !== undefined) {
            unitSizeSelect.val(detailSize);
        }
    }

    function setInformationAndUnitPrice(selectedOption) {
        let unitPriceInput = $('#unit_price');
        let informationSelect = $('#information');

        let unitPrice = selectedOption.data("unit-price");
        let information = selectedOption.data("information");

        if (unitPrice !== undefined) {
            unitPriceInput.val(numberWithCommas(unitPrice));
        } else {
            unitPriceInput.val("");
        }

        if (information !== undefined) {
            informationSelect.val(information);
        }
    }

    // Manipulasi form
    $("#customSize").hide();
    function customSize() {
        var selectedValue = $('#status_custom_product').val();
        if (selectedValue === "0") {
            $("#customSize").hide();
            $("#defaultSize").show();
            $("#hargaSatuanLabel").text("Harga Satuan");
            $("#panjang").removeClass("validate-form").val("");
            $("#lebar").removeClass("validate-form").val("");
        } else if(selectedValue === "1") {
            $("#customSize").show();
            $("#hargaSatuanLabel").text("Harga Permeter");
            $("#detail_size").removeClass("validate-form");
            $("#panjang").addClass("validate-form");
            $("#lebar").addClass("validate-form");
            $("#defaultSize").hide();
        }
    }

    $.ajax({
        url: `/v1/product`,
        method: 'GET',
        dataType: 'json',
        success: function (response) {

            if (response.message === "success") {
                populateProductOptions(response.data);

                let productNameSelect = $('#id_product');
                let informationSelect = $('#information');
                let detailSizeSelect = $('#detail_size');

                productNameSelect.on('change', function () {
                    let selectedProductId = $(this).val();
                    let selectedProduct = response.data.find(product => product.id === selectedProductId);

                    if (selectedProduct) {
                        populateInformationAndDetailSizeOptions(selectedProduct);

                        let selectedInformationId = informationSelect.val();
                        let selectedOption = informationSelect.find(`[value="${selectedInformationId}"]`);

                        let selectStatusCustom = $("#status_custom_product").val(selectedProduct.status_custom_product);

                        setUnitPriceAndSize(selectedOption, selectStatusCustom);
                        customSize(selectStatusCustom);
                    }
                });

                informationSelect.on('change', function () {
                    let selectedOption = $(this).find(":selected");
                    setUnitPriceAndSize(selectedOption);
                });

                detailSizeSelect.on('change', function () {
                    let selectedOption = $(this).find(":selected");
                    setInformationAndUnitPrice(selectedOption);
                });
            }
        },
        error: function () {
            console.log('Terjadi kesalahan dalam menghubungi API.');
        }
    });

    // alert validation
    function alertValidation(message) {
        Swal.fire({
            title: 'Peringatan !',
            text: message,
            icon: 'warning',
            timer: 5000,
            showConfirmButton: true,
            confirmButtonText: 'Ok',
            confirmButtonColor: '#FFAD46',
        });
    }
    // alert errors category
    function alertError() {
        Swal.fire({
            title: 'Error',
            text: 'Product yang anda pilih berbeda kategori!',
            icon: 'error',
            showCancelButton: false,
            confirmButtonText: 'Ok',
            confirmButtonColor: '#F25961',
        });
    }

    // Valitaion rule
    function validationRule() {
        $('.validate-form').removeClass('border-danger');
        let isValid = true;

        $('.validate-form').each(function () {
            let isSelect2 = $(this).hasClass('select2-hidden-accessible');

            if (isSelect2) {
                let select2Input = $(this).next().find('.select2-selection');
                if ($(this).val()) {
                    select2Input.removeClass('border-danger');
                } else {
                    select2Input.addClass('border-danger');
                    isValid = false;
                }
            } else if ($(this).val() === "" || ($(this).is('select') && $(this).find('option:selected').val() === "")) {
                $(this).addClass('border-danger');
                isValid = false;
            }

            if ($(this).attr('id') === 'qty') {
                let qtyValue = parseInt($(this).val(), 10);
                if (qtyValue === 0) {
                    $(this).addClass('border-danger');
                    isValid = false;
                }
            }
        });

        return isValid;
    }


    // * Transaction Item
    $('input[name="discount_total"]').on('input', function() {
        formatUnitPriceInput(this, true);
    });
    // create storage item
    function createItemOrderStorage(params) {
        const id_detail_product = params.id_detail_product;
        const detail_size = params.detail_size;
        let existingOrderItems = JSON.parse(localStorage.getItem('orders')) || [];

        if (existingOrderItems.length > 0) {
            const firstCategory = existingOrderItems[0].category;
            const category = params.category;
            if (category !== firstCategory) {
                alertError();
                return;
            }
        }

        let existingOrderItem = existingOrderItems.find((orderItem) => orderItem.id_detail_product === id_detail_product && orderItem.detail_size === detail_size);

        if (existingOrderItem) {
            existingOrderItem.qty += params.qty;
            existingOrderItem.total_price += params.total_price;
        } else {
            existingOrderItems.push(params);
        }

        localStorage.setItem('orders', JSON.stringify(existingOrderItems));
    }
    // update order
    function updateItemOrderStorage(index, updatedItem) {
        let orderItems = JSON.parse(localStorage.getItem('orders')) || [];

        if (orderItems.length > 0) {
            const firstCategory = orderItems[0].category;
            const category = updatedItem.category;
            if (category !== firstCategory) {
                alertError();
                return;
            }
        }

        if (index >= 0 && index < orderItems.length) {
            orderItems[index] = updatedItem;
            localStorage.setItem('orders', JSON.stringify(orderItems));
        }
    }

    // save order
    $(document).on('click', '#saveOrder', function () {
        const isValid = validationRule(this);
        if (!isValid) {
            alertValidation('Input tidak boleh kosong');
            return;
        }

        // perhitungan panjang x lebar
        const initialUnitPrice = parseInt($('#unit_price').val().replace(/[^0-9]/g, '')) || 0;
        const panjang = parseFloat($('#panjang').val().replace(',', '.')) || 0;
        const lebar = parseFloat($('#lebar').val().replace(',', '.')) || 0;
        let calculateUnitPrice = Math.round(panjang * lebar * initialUnitPrice);

        let index = $('#id').val();

        const selectedProductId = $('#id_product').val();
        const selectedProductOption = $('#id_product option:selected');
        const unitProducts = $('#id_product').data('unit_product');
        const selectedProduct = unitProducts.find(item => item.id === selectedProductId);
        const unit = selectedProduct ? selectedProduct.unit_product : null;
        const category = selectedProduct ? selectedProduct.category : null;

        // menampung inputan panjang x lebar di detailsize
        const isCustomSize = $("#status_custom_product").val();
        let detailSize = "";
        if (isCustomSize == "1") {
            detailSize = isCustomSize ? `${panjang} x ${lebar}` : '';
        } else {
            detailSize = $('#detail_size').val();
        }

        const unitPrice = parseInt($('#unit_price').val().replace(/[^0-9]/g, '')) || 0;
        const discountTotal = parseFloat($('#discount_total').val()) || 0;

        // Buat objek orderItem
        const orderItem = {
            id_product: selectedProductId,
            id_detail_product: $('#information').val(),
            status_custom_product: $('#status_custom_product').val(),
            product_name: selectedProductOption.text().split(' - ')[0], // Ambil hanya nama produk
            unit_product: unit,
            category: category,
            information: $('#information option:selected').text(),
            panjang: $('#panjang').val(),
            lebar: $('#lebar').val(),
            detail_size: detailSize,
            note: $('#note').val(),
            qty: parseInt($('#qty').val()),
            initialUnitPrice: initialUnitPrice,
            unit_price: isCustomSize === "0" ? initialUnitPrice : calculateUnitPrice,
            discount_total: discountTotal
        };

        orderItem.sub_total_price = orderItem.qty * orderItem.unit_price;
        orderItem.discount_total = (orderItem.discount_total / 100) * orderItem.sub_total_price;
        orderItem.total_price = orderItem.sub_total_price - orderItem.discount_total;

        // Set item order to storage
        if (index) {
            updateItemOrderStorage(index, orderItem);
        } else {
            createItemOrderStorage(orderItem);
        }

        getAllOrders();

        // Close the modal
        $('#listOrderModal').modal('hide');
    });

    $(document).on('click', '#addListOrder', function() {
        $('#upsertListOrder')[0].reset();
        $('#id').val('');
        $('#status_custom_product').val(" ");
        $('#customSize').hide();
        $('#defaultSize').show();
        $('#id_product').empty();
        $('#id_product').next().find('.select2-selection').removeClass('border-danger');
        $('#information').empty();
        $('#information').append($('<option>', {
            value: "",
            text: "select"
        }));
        $('#detail_size').empty();
        $('#detail_size').append($('<option>', {
            value: "",
            text: "select"
        }));

        $('#listOrderTitle').text('Tambah Pesanan');
        $('.validate-form').removeClass('border-danger');
        $('#listOrderModal').modal('show');
        $('#saveOrder').text('Tambah pesanan');
    });

    // modal update
    $('#tBody').on('click', '.editOrder', function() {
        let index = $(this).data('index');
        const existingData = localStorage.getItem('orders');
        let orderItems = existingData ? JSON.parse(existingData) : [];

        if (index !== undefined && index >= 0 && index < orderItems.length) {
            const orderItem = orderItems[index];
            const discount = (orderItem.discount_total / orderItem.sub_total_price) * 100;

            $('#id').val(index);
            var newOption = new Option(orderItem.product_name, orderItem.id_product, true, true);
            $('#id_product').append(newOption).trigger('change');

            $.ajax({
                url: `/v1/product/get/${orderItem.id_product}`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.message === "success") {
                        let informationSelect = $('#information');
                        let detailSizeSelect = $('#detail_size');
                        informationSelect.empty();
                        detailSizeSelect.empty();

                        $.each(response.data.detail_product, function(index, detail) {
                            let informationOption = $('<option>', {
                                value: detail.id,
                                text: detail.information
                            });
                            informationSelect.append(informationOption);
                            informationOption.data("detail-size", detail.detail_size);
                            informationOption.data("unit-price", detail.unit_price);

                            let detailSizeOption = $('<option>', {
                                value: detail.detail_size,
                                text: detail.detail_size
                            });
                            detailSizeSelect.append(detailSizeOption);
                            detailSizeOption.data("information", detail.id);
                            detailSizeOption.data("unit-price", detail.unit_price);
                        });

                        $('#information').val(orderItem.id_detail_product);
                        $('#detail_size').val(orderItem.detail_size);
                    }
                },
                error: function() {
                    console.log('Terjadi kesalahan dalam menghubungi API.');
                }
            });

            if (orderItem.status_custom_product === "1") {
                $('#customSize').show();
                $('#defaultSize').hide();
                $('#panjang').val(orderItem.panjang);
                $('#lebar').val(orderItem.lebar);
                $('#unit_price').val(numberWithCommas(orderItem.initialUnitPrice));
                $("#hargaSatuanLabel").text("Harga Satuan(meter)");
            } else if (orderItem.status_custom_product === "0") {
                $('#customSize').hide();
                $('#defaultSize').show();
                $('#unit_price').val(numberWithCommas(orderItem.unit_price));
                $("#hargaSatuanLabel").text("Harga Satuan");
            }


            $('#discount_total').val(discount);
            $('#note').val(orderItem.note);
            $('#qty').val(orderItem.qty);
            $('#listOrderTitle').text('Update pesanan');
            $('.validate-form').removeClass('border-danger');
            $('#listOrderModal').modal('show');
            $('#saveOrder').text('Update pesanan');
        }
    });

    // get all order items
    function getAllOrders() {
        let tBody = $('#tBody');
        tBody.empty();
        let sub_total_payment = 0;
        let orderList = JSON.parse(localStorage.getItem('orders')) || [];

        orderList.forEach(function(val, index) {
            tBody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${val.product_name}</td>
                    <td>${val.information}</td>
                    <td>${val.detail_size}</td>
                    <td>${val.qty}</td>
                    <td>${formatCurrencyCustom(val.unit_price)}</td>
                    <td>-${formatCurrencyCustom(val.discount_total)}</td>
                    <td>${formatCurrencyCustom(val.total_price)}</td>
                    <td>
                        <a href="#" class="editOrder text-primary pe-auto " data-index="${index}">
                           <u>Edit<u>
                        </a> <span class = "px-3">|</span>
                        <a href="#" class="deleteOrder  text-danger pe-auto " data-index="${index}">
                            <u>Hapus<u>
                        </a>
                    </td>
                </tr>
            `);
            sub_total_payment += val.total_price;
        });

        $('#sub_total_payment').text(formatRupiah(sub_total_payment));
        if (orderList.length === 0) {
            $('#emptyMessage').show();
            $('#tableContainer').hide();
            $("#continueButton").prop("disabled", true).removeAttr("href").addClass("text-light");
        } else {
            $('#emptyMessage').hide();
            $('#tableContainer').show();
            $("#continueButton").prop("disabled", false).attr("href", "/cashiertransaction");
        }
    }
    getAllOrders();

    function deleteItemOrderStorage(index) {
        const existingData = localStorage.getItem('orders');
        let orderItems = existingData ? JSON.parse(existingData) : [];

        if (index >= 0 && index < orderItems.length) {
            orderItems.splice(index, 1);
            localStorage.setItem('orders', JSON.stringify(orderItems));
        }
    }

    $('#tBody').on('click', '.deleteOrder', function() {
        let index = $(this).data('index');
        Swal.fire({
            title: 'Hapus!',
            text: 'Anda tidak bisa mengembalikan ini',
            icon: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya',
            confirmButtonColor: '#6861CE',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (index !== undefined) {
                    deleteItemOrderStorage(index);
                    getAllOrders();
                }
            }
        });
    });
});
</script>

@endsection

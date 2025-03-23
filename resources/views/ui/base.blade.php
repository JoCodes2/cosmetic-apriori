<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('Image/maxie.png') }}" type="image/png" />
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>
<body class="bg-gray-100">
    {{-- navbar --}}
    @include('ui.navbar')
    <!-- Alert Container -->
    <div id="alert-container" class="fixed top-5 right-5 z-50"></div>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    @include('ui.footer')

    <!-- Sweet Alert -->
    <!-- jQuery Validate -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.3.0/alpine-ie11.js" integrity="sha512-6m6AtgVSg7JzStQBuIpqoVuGPVSAK5Sp/ti6ySu6AjRDa1pX8mIl1TwP9QmKXU+4Mhq/73SzOk6mbNvyj9MPzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    $(document).ready(function () {
        let searchTimeout;

        function performSearch(query, targetList, targetDropdown) {
            if (query.length < 3) {
                $(targetDropdown).hide();
                return;
            }

            clearTimeout(searchTimeout); // Hentikan request sebelumnya
            searchTimeout = setTimeout(() => {
                $.ajax({
                    url: "/v1/product/search",
                    type: "GET",
                    data: { keyword: query },
                    success: function (response) {
                        console.log(response);

                        if (response.code === 200 && response.data.length > 0) {
                            let searchList = response.data.map(item => `
                                <li class="p-2 hover:bg-pink-100 cursor-pointer flex justify-between items-center search-item"
                                    data-id="${item.id}" data-name="${item.name}">
                                    <span>${item.name}</span>
                                </li>
                            `).join("");

                            $(targetList).html(searchList);
                            $(targetDropdown).show();
                        } else {
                            $(targetDropdown).hide();
                        }
                    },
                    error: function () {
                        $(targetDropdown).hide();
                    }
                });
            }, 300); // Delay 300ms untuk mencegah spam request
        }

        function setupSearch(inputSelector, listSelector, dropdownSelector, clearSelector) {
            $(inputSelector).on("input", function () {
                let keyword = $(this).val().trim();
                performSearch(keyword, listSelector, dropdownSelector);
                $(clearSelector).toggle(keyword.length > 0);
            });

            $(clearSelector).on("click", function () {
                $(inputSelector).val("").trigger("input");
                $(this).hide();
            });
        }

        setupSearch("#search-box", "#search-list", "#search-results", "#clear-search");
        setupSearch("#mobile-search-box", "#mobile-search-list", "#mobile-search-results", "#mobile-clear-search");

        // Klik di luar dropdown untuk menyembunyikan hasil pencarian
        $(document).click(function (e) {
            if (!$(e.target).closest("#search-box, #search-results").length) {
                $("#search-results").hide();
            }
            if (!$(e.target).closest("#mobile-search-box, #mobile-search-results").length) {
                $("#mobile-search-results").hide();
            }
        });

        // Toggle Mobile Menu
        $("#menu-toggle").on("click", function () {
            $("#mobile-menu").slideToggle();
        });

        // Event klik item hasil pencarian
        $(document).on("click", ".search-item", function () {
            let productId = $(this).data("id");
            if (productId) {
                window.location.href = `/result?product_id=${productId}`;
            }
        });
    });
    </script>











    @yield('script')
    <!-- Alpine.js v3 CDN -->


</body>
</html>
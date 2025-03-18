<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Maxie</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    @include('layouts.style')
</head>

<body>
    <div class="wrapper">
        {{-- navbar --}}
        @include('layouts.navbar')
        {{-- end navbar --}}
        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                @yield('content')
            </div>
            @include('layouts.footer')
        </div>
    </div>
    @include('layouts.script')
    @yield('script')

</body>

</html>

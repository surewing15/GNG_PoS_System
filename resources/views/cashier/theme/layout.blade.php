<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('cashier.theme.header')
</head>

<body class="nk-body bg-lighter npc-general has-sidebar">
    <div class="nk-app-root">
        <div class="nk-main">
            @include('cashier.theme.sidemenu')
            <div class="nk-wrap">
                @include('cashier.theme.header-top')
                <div class="nk-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                @include('cashier.theme.footer')
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/bundle.js?ver=3.0.3"></script>
    <script src="/assets/js/scripts.js?ver=3.0.3"></script>
    <script src="/assets/js/libs/datatable-btns.js?ver=3.0.3"></script>

    <!-- Page Specific Scripts -->
    @yield('scripts')

    <script>
        // Global AJAX setup for CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>

</html>

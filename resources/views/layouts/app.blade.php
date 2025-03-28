<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }} zxx" class="js">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/vendor/images/favicon.png">
    <link rel="stylesheet" href="/vendor/assets/css/dashlite.css?ver=3.0.3">
    <link id="skin-default" rel="stylesheet" href="/vendor/assets/css/theme.css?ver=3.0.3">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- In your layout -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .form-control,
        .dual-listbox .dual-listbox__search,
        div.dataTables_wrapper div.dataTables_filter input {
            display: block;
            width: 100%;
            padding: 0.4375rem 1rem;
            font-size: 0.8125rem;
            font-weight: 400;
            line-height: 1.25rem;
            color: #3c4d62;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #dbdfea;
            appearance: none;
            border-radius: 4px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        html,
        body {
            overflow-x: hidden;
        }
    </style>
    @livewireStyles
    <style>
        .nk-sidebar .nk-menu-link:hover {
            background-color: #d30000 !important;
            color: #ffffff !important;
        }

        .nk-menu-link:hover .nk-menu-icon,
        .nk-menu-item.active>.nk-menu-link .nk-menu-icon,
        .nk-menu-item.current-menu>.nk-menu-link .nk-menu-icon {
            color: #ffffff;
        }

        .nk-sidebar .nk-menu-item.active>.nk-menu-link {
            background-color: #d30000 !important;
            color: #ffffff !important;
        }

        .nk-menu-icon {
            color: #000000;
        }

        /* Set the sidebar text to black by default */
        .nk-menu-link .nk-menu-text {
            color: #000000;
            /* Black text by default */
        }

        /* Ensure the text remains black on hover */
        .nk-menu-link:hover .nk-menu-text {
            color: #ffffff;
            /* Black text on hover */
        }

        /* Set the text to white when the menu item is active */
        .nk-menu-item.active>.nk-menu-link .nk-menu-text {
            color: #ffffff;
            /* White text when active */
        }

        .nk-menu-link {
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        .nk-menu-link .nk-menu-icon em {
            transition: color 0.4s ease;
        }
    </style>


</head>

<body class="nk-body bg-lighter npc-general has-sidebar font-sans antialiased ">
    <div class="nk-app-root">
        <div class="nk-main ">
            <x-navbar-sidemenu />
            <div class="nk-wrap ">
                <x-navbar-topmenu />
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">

                                <!-- Page Header -->
                                @if (isset($header))
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h3 class="nk-block-title page-title mb-1">{{ $header }}</h3>
                                                @if (isset($subheader))
                                                    <p>{{ $subheader }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Page Content -->
                                <main>
                                    {{ $slot }}
                                </main>

                            </div>
                        </div>

                        @stack('modals')
                        @livewireScripts
                    </div>
                </div>
                <x-footer />
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="/vendor/assets/js/bundle.js?ver=3.0.3"></script>
    <script src="/vendor/assets/js/scripts.js?ver=3.0.3"></script>
    <script src="/vendor/assets/js/libs/datatable-btns.js?ver=3.0.3"></script>
    <script src="/vendor/assets/js/charts/chart-ecommerce.js?ver=3.0.3"></script>
    <script src="/vendor/assets/js/example-sweetalert.js?ver=3.0.3"></script>
    <script src="/vendor/assets/js/example-chart.js?ver=3.0.3"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Saved Successfully',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    @if (session('delete'))
        <script>
            Swal.fire({
                icon: 'success', // Use 'success' for a successful deletion
                title: 'Deleted',
                text: '{{ session('delete') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif


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

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Dashboard - Admin</title>
    <!-- CSS files -->
    <link href="/dist/css/tabler.min.css?1692870487" rel="stylesheet" />
    <link href="/dist/css/tabler-flags.min.css?1692870487" rel="stylesheet" />
    <link href="/dist/css/tabler-payments.min.css?1692870487" rel="stylesheet" />
    <link href="/dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet" />
    <link href="/dist/css/demo.min.css?1692870487" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    {{-- Js --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class=" layout-fluid">
    <script src="/dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
        <!-- Navbar -->
        <x-navigation.p_header />
        <x-navigation.s_header />
        <div class="page-wrapper">
            <!-- Page header -->

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">
                        @yield('content')
                    </div>
                </div>
            </div>
            <x-navigation.footer />
        </div>
    </div>
    <!-- Libs JS -->
    <script src="/dist/libs/apexcharts/dist/apexcharts.min.js?1692870487" defer></script>
    <script src="/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487" defer></script>
    <script src="/dist/libs/jsvectormap/dist/maps/world.js?1692870487" defer></script>
    <script src="/dist/libs/jsvectormap/dist/maps/world-merc.js?1692870487" defer></script>
    <!-- Tabler Core -->
    <script src="/dist/js/tabler.min.js?1692870487" defer></script>
    <script src="/dist/js/demo.min.js?1692870487" defer></script>
</body>

</html>

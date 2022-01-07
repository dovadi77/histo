<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title')</title>
    <link href="/css/app.css" rel="stylesheet" />
    <link href="/css/admin.css" rel="stylesheet" />
    @yield('custom_styles')
</head>

<body class="sb-nav-fixed">
    <section>
        <x-navbar></x-navbar>
    </section>
    <section id="layoutSidenav">
        <x-sidebar></x-sidebar>
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
        </div>
    </section>
    <script src="/js/app.js"></script>
    @yield('custom_scripts')
</body>

</html>

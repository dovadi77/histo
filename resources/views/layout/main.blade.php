<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- CSS -->
    <link href="css/app.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <!-- Font Awesome-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    @yield('title')
</head>

<body class="sb-nav-fixed">
    <section>
        <x-navbar></x-navbar>
    </section>
    <section id="layoutSidenav">
        <x-sidebar></x-sidebar>
        <div id="layoutSidenav_content">
            <main>
                @yield('data')
            </main>
        </div>
    </section>
    <script src="js/app.js"></script>
    <script src="js/datatables.js"></script>
    @yield('custom_scripts')
</body>

</html>

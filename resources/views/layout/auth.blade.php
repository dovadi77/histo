<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <link href="/css/app.css" rel="stylesheet" />
    <link href="/css/auth.css" rel="stylesheet" />
    @yield('custom_styles')
</head>

<body>
    @yield('content')
    <script src="/js/app.js"></script>
    @yield('custom_scripts')
</body>

</html>

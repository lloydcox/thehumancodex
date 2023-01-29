<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>THC - Admin Panel</title>

    @include('admin.elements.links')

</head>

<body id="page-top">

    @yield('content')

    @include('admin.elements.scripts')
    @include('admin.elements.data_tables')
</body>

</html>

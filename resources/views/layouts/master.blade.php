<html>
<!-- Header -->
@include('dopetrope.partials.header')

<body style="background: lightcoral">
    
    @section('menu')
        Contenido del menu
    @show

    <div class="container">
        @yield('content')
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device.width, initial-scale=1">
        <title>Produits</title>
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"  crossorigin="anonymous">
    </head>
    <body>
    <div>
         <div class="col-md-13">
           @include('partials.menu')
        </div>
         <div>
            <div class="divContent col-md-13">
                @yield('content')
            </div>
        </div>
            <div class="col-md-13">
                @include('front.footer')
            </div>
        @section('scripts')
            <script src="{{asset('js/app.js')}}"></script>
        @show
    </body>
</html>
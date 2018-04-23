<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>NEM integration app</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{URL::asset('css/app.css')}}" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div class="flex-center position-ref full-height">
                        
            @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
            @endif


            <div class="content">
                <div class="title m-b-md">
                    <div class="col-md-4">
                        <img src="{{asset('images/dimcoin.png')}}" width="100">
                    </div>
                    <div class="col-md-8">apps</div>
                </div>

                <div class="links">
                    <a href="https://github.com/evias/nem-php" target="_blank">nem-php Library</a>
                    <a href="https://github.com/evias" target="_blank">by Greg S.</a>
                    <!--<a href="/getting-started">Getting Started</a>-->
                    <!--<a href="/nem-sandbox">NEM Sandbox</a>-->
                </div>
            </div>
        </div>
    </body>
</html>

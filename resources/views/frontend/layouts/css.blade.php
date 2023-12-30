<!-- favicon -->
<link rel="icon" sizes="57x57" href="{{ asset('favicon.ico') }}" >

<link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/lightpick.css') }}" >
<link href="{{ asset('frontend/css/slick.css ') }}" rel="stylesheet">
<link href="{{ asset('frontend/css/slick-theme.css') }}" rel="stylesheet">

@vite([
        'resources/frontend/css/auth.css',
        'resources/frontend/css/style.css',
        'resources/frontend/css/dev1.css',
        'resources/frontend/css/cart.css',
        'resources/frontend/css/custom.css',
        'resources/frontend/css/shipping-address.css',
        'resources/frontend/css/place-order.css',
        'resources/frontend/css/vendor-payment.css'
    ], 'assets')

<link type="text/css" href="{{ asset('frontend/css/font-awesome.css') }}" rel="stylesheet" />
<link type="text/css" href="{{ asset('frontend/css/ufonts.css') }}" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" rel="stylesheet" />

<style>
    #toast-container>div {
        opacity: 1;
    }
</style>

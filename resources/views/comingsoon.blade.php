<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <style>
      body {
        background-image: url('/images/demo/fomo-coming-soon.jpg');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        width: 100%;
        height: 100vh;
        position: relative;
        font-family: 'Muli';
      }
      .coming-soon-message {
        color: white;
        min-height: 400px;
        width: 100%;
        max-width: 550px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate3d(-50%, -50%, 0);
      }
      .logo {
        width: 120px;
        color: white;
        margin: auto;
      }
      .logo svg {
        color: white;
      }
      .main-message {
        font-size: 38px;
        text-align: center;
        line-height: 1.2;
        margin: 60px auto 40px
      }
      #early-excess-invite-form {
        text-align: center;
      }
      #early-excess-invite-form label {
        font-weight: 400;
        display: block;
        margin-bottom: 40px;
      }
      #early-excess-invite-form input[type="email"] {
        background-color: transparent;
        border: none;
        outline: none;
        display: block;
        margin: auto;
        margin-bottom: 25px;
        text-align: center;
        width: 70%;
        border-bottom: 1px solid white;
        padding-bottom: 10px;
      }
      #early-excess-invite-form input[type="email"]::placeholder {
        color: #CCCCCC;
        font-size: 12px;
      }
      #early-excess-invite-form input[type=submit] {
        background: transparent;
        border: none;
        outline: none;
        text-transform: uppercase;
        font-weight: 900;
        letter-spacing: 1px;
        color: white;
        font-size: 12px;
      }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    @include('layouts.partials.popup')
    <div class="coming-soon-message">
      <div class="logo">
        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 350 100">
            <path fill="white" d="M183.29,23.58h9.54l16.52,33.64,16.52-33.64h9.54V75.51h-7.77V40.37L212.09,69.86h-5.48L191.06,40.37V75.51h-7.78Z"/>
            <path fill="white" d="M116.35,23.56A26.44,26.44,0,1,0,142.78,50,26.44,26.44,0,0,0,116.35,23.56Zm0,44.62A18.18,18.18,0,1,1,134.52,50,18.18,18.18,0,0,1,116.35,68.18Z"/>
            <path fill="white" d="M298.56,23.56A26.44,26.44,0,1,0,325,50,26.44,26.44,0,0,0,298.56,23.56Zm0,44.62A18.18,18.18,0,1,1,316.74,50,18.18,18.18,0,0,1,298.56,68.18Z"/>
            <polygon fill="white" points="60.96 31 60.96 23.58 25 23.58 25 76.44 32.77 76.44 32.77 54.34 55.78 54.34 55.78 46.92 32.77 46.92 32.77 31 60.96 31"/>
        </svg>
      </div>
      <div class="main-message">
        A full customization experience for your watch
      </div>
      <form action="/early-excess-invite" id="early-excess-invite-form" method="post">
        {{ csrf_field() }}
        <label for="email">Sign up for your early access invite!</label>
        <input type="email" name="email" placeholder="Enter your email here" autocomplete="off">
        <input type="submit" value="Notify Me">
      </form>
    </div>

    <!-- Scripts -->
    {{-- <script src="/js/app.js"></script> --}}
    {{-- <script src="/js/jquery-1.10.2.js" type="text/javascript"></script> --}}
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="/js/instafeed.min.js"></script>
    <script src="/js/lightslider.min.js"></script>
    <script src="/js/konva.js"></script>
    <script src="/js/admin/sweetalert.min.js"></script>
    @stack('scripts')
    <script src="/js/main.js"></script>
</body>
</html>

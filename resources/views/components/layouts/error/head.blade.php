@props([
    'title',
])

<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>{!! $title !!} - {{ config('app.name') }}</title>

    <base href="{{ config('app.url') . '/' }}">

    <x-layouts.pwa.head />

    <!-- Favicon -->
    <link rel="icon" href="{{url('/img/christabel.ico') }}" type="image/png">

    <!--Icons-->
    <link rel="stylesheet" href="{{url('/css/fonts/material-icons/style.css?v=' . version('short')) }}" type="text/css">

     <!-- Font -->
    <link rel="stylesheet" href="{{url('/vendor/quicksand/css/quicksand.css?v=' . version('short')) }}" type="text/css">

    <!-- Css -->
    <link rel="stylesheet" href="{{url('/css//third_party/swiper-bundle.min.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{url('/css/element.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{url('/css/app.css?v=' . version('short')) }}" type="text/css">

    @stack('css')

    @stack('stylesheet')

    <script type="text/javascript"><!--
        var url = '{{ url("/") }}';
        var app_url = '{{ config("app.url") }}';
    //--></script>

    @stack('js')

    <script type="text/javascript"><!--
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;

        var flash_notification = {!! (session()->has('flash_notification')) ? json_encode(session()->get('flash_notification')) : 'false' !!};
    //--></script>

    {{ session()->forget('flash_notification') }}

    @stack('scripts')

    @stack('head_end')
</head>

<!DOCTYPE html>
<html lang="en" @if(Request::is('/')) style="background-size: cover;" @endif)>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{!! getSettingValueByKeyCache('name') !!}</title>
    <link rel="icon" href="{!!  getSettingValueByKeyCache('logo') !!}" type="image/x-icon" />
    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">

    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.3/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.1/css/jquery-weui.min.css">

    @yield('css')
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lte IE 9]>
            <script src="{{ asset('vendor/html5shiv.min.js') }}"></script>
            <script src="{{ asset('vendor/respond.min.js') }}"></script>
        <![endif]-->
</head>
<body style="position: relative;">
    
    <!--[if lte IE 8]>
        <script>
            alert("您正在使用的浏览器版本过低，为了您的最佳体验，请先升级浏览器。");window.location.href="http://support.dmeng.net/upgrade-your-browser.html?referrer="+encodeURIComponent(window.location.href);
        </script>
    <![endif]-->
    <!-- Add your site or application content here -->

    @yield('content')

    <script src="https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-weui/1.2.1/js/jquery-weui.min.js"></script>
    <script src="{{asset('js/zcjy.js')}}"></script>
    <script src="{{ asset('vendor/layer/layer.js') }}"></script>

    @yield('js')
</body>
</html>

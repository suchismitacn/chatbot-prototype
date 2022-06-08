<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        body {
            font-family: "Source Sans Pro", sans-serif;
            margin: 0;
            padding: 0;
            background: radial-gradient(#57bfc7, #45a6b3);
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .content {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content" id="app">
        {{-- <botman-tinker api-endpoint="/botman"></botman-tinker> --}}
        Click on the chat widget to get started
    </div>
</div>
<script>
    var botmanWidget = {
        title: 'ChatBot',
        // introMessage: '<h4>Welcome to QuizBot!</h4><p>Type: <ul><li>/start - to start.</li><li>/bye - to exit.</li><li>/mail - to get chat transcript.</li></ul></p>',
        introMessage: 'Welcome to ChatBot! Send me a hi to get started',
        userId: "{{request()->session()->getId()}}",
        displayMessageTime: true
    }
</script>
<script src="/js/app.js"></script>
<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
</body>
</html>
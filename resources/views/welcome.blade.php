<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bareagroup</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            body,html{
                padding:0;
                margin:0;
                height:100%;
                
            }

            body{
                background:url(img/bg.jpg) 0 0 / contain repeat-x;
            }

            .landingpage{
                height: 100%;
                width: 100%;
                background:url(img/landingpage.jpg) center 0 / contain no-repeat;
            }
        </style>
    </head>
    <body>
        <div class="landingpage"></div>
    </body>
</html>

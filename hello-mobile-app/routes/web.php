<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta name='viewport' 
              content='width=device-width, initial-scale=1'>
        <title>Hello Mobile</title>
    </head>
    <body style='text-align:center;margin-top:100px;
                 font-family:sans-serif;'>
        <h1>Hello World from NativePHP Mobile 📱</h1>
    </body>
    </html>
    ";
});
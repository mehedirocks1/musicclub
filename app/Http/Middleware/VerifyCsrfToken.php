<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $addHttpCookie = true;

    // আপনার রাউটগুলোর path (prefix সহ) দিন — শুরুতে slash দেবেন না
  protected $except = [
    'sslcommerz/ipn',
    'sslcommerz/success',
    'sslcommerz/failure',
    'sslcommerz/cancel',
];

}

<?php

// config/shopify.php
return [
    'api_key' => env('SHOPIFY_API_KEY'),
    'api_secret' => env('SHOPIFY_API_SECRET'),
    'api_scope' => env('SHOPIFY_API_SCOPE'),
    'api_version' => env('SHOPIFY_API_VERSION'),
    'shop_url' => env('SHOPIFY_SHOP_URL'),
    'access_token' => env('SHOPIFY_ACCESS_TOKEN'),
];

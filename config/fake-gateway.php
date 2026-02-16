<?php

return [
    /**
     * The URL prefix for all the fake gateway routes.
     */
    'prefix' => 'fake',

    /**
     * The name prefix for all the fake gateway routes.
     */
    'route_name_prefix' => 'fake-gateway.',

    /**
     * Set this to true to enable the built-in demo routes
     * (/checkout, /payment/success, etc.) for quick testing.
     * It is recommended to keep this false in production.
     */
    'enable_demo_routes' => env('FAKE_GATEWAY_ENABLE_DEMO', true),
];
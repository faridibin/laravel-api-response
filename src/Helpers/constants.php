<?php

/**
 * ApiResponse represents an HTTP response in JSON format.
 *
 * Note that this class does not force the returned JSON content to be an
 * object. It is however recommended that you do return an object as it
 * protects yourself against XSSI and JSON-JavaScript Hijacking.
 *
 * @author Farid Adam <me@faridibin.tech>
 */

define('LARAVEL_API_RESPONSE_CONFIG', 'api-response');
define('LARAVEL_API_RESPONSE_KEY', 'laravel-api-response');
define('LARAVEL_API_RESPONSE_AUTH_HEADER', 'Authorization');
define('LARAVEL_API_RESPONSE_URI_CASES', ['camel', 'slug', 'snake', 'spinal']);
define('LARAVEL_API_RESPONSE_URI_CASE', 'snake');

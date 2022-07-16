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
define('LARAVEL_API_RESPONSE_FORMATS', ['json', 'xml', 'yaml']);
define('LARAVEL_API_RESPONSE_FORMAT', 'json');
define('LARAVEL_API_RESPONSE_XML_CONFIG', [
    'root' => 'response',
    'namespace' => null,
    'prefix' => null,
    'encoding' => 'UTF-8',
    'version' => '1.0'
]);
define('LARAVEL_API_RESPONSE_XML_KEYWORDS', [
    'data'
]);

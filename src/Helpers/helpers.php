<?php

use Illuminate\Support\Str;

/**
 * Helper functions for the API response.
 *
 * @author Farid Adam <me@faridibin.tech>
 */

if (!function_exists('api_response')) {
    /**
     * Return a new api response from the application.
     *
     * @param  string|array|null  $content
     * @param  int  $status
     * @param  array  $headers
     *
     * @return \Faridibin\LaravelApiResponse\ApiResponse
     */
    function api_response($content = '', $status = 200, array $headers = [])
    {
        $apiResponse = app(LARAVEL_API_RESPONSE_KEY);

        if (func_num_args() === 0) {
            return $apiResponse;
        }

        if (is_array($content)) {
            $apiResponse->setData($content);
        }

        if (is_string($content)) {
            $apiResponse->setMessage($content);
        }

        return $apiResponse->setStatusCode($status)->setHeaders($headers);
    }
}

if (!function_exists('format_with_uri_case')) {
    /**
     * Validates uri case against supported cases.
     *
     * @param  string  $case
     *
     * @return string
     */
    function validate_uri_case(string $case)
    {
        if (in_array($case, LARAVEL_API_RESPONSE_URI_CASES)) {
            return ($case === 'spinal') ? 'slug' : $case;
        }

        return LARAVEL_API_RESPONSE_URI_CASE;
    }

    /**
     * Formats data with case provided.
     *
     * @param  array  $data
     * @param  string  $case
     *
     * @return mixed
     */
    function format_with_uri_case(array $data, string $case)
    {
        return \collect($data)->mapWithKeys(function ($value, $key) use ($case) {
            $case = validate_uri_case($case);

            if (is_array($value)) {
                return [Str::of($key)->$case()->toString() => format_with_uri_case($value, $case)];
            }

            return [Str::of($key)->$case()->toString() => $value];
        })->all();
    }
}

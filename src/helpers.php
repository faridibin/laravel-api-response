<?php

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
        $response = app(LARAVEL_API_RESPONSE_KEY);

        if (func_num_args() !== 0) {
            // if (is_array($content)) {
            //     $apiResponse->setData($content);
            // }

            // if (is_string($content)) {
            //     $apiResponse->setMessage($content);
            // }

            // return $apiResponse->setStatusCode($status)->setHeaders($headers);
        }

        return $response;
    }
}

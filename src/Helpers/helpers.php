<?php

use Illuminate\Support\Str;

if (!function_exists('format_with_uri_case')) {
    /**
     * Validates uri case against supported cases.
     *
     * @param  string  $case
     * @return string
     */
    function validate_uri_case(string $case)
    {
        if (in_array($case, LARAVEL_API_JSON_RESPONSE_URI_CASES)) {
            return ($case === 'spinal') ? 'slug' : $case;
        }

        return LARAVEL_API_JSON_RESPONSE_URI_CASE;
    }

    /**
     * Formats data with case provided.
     *
     * @param  array  $data
     * @param  string  $case
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

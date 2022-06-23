<?php

namespace Faridibin\LaravelApiResponse\Traits;

use Illuminate\Http\Response;

/**
 * ApiResponse represents an HTTP response in JSON format.
 *
 * Note that this class does not force the returned JSON content to be an
 * object. It is however recommended that you do return an object as it
 * protects yourself against XSSI and JSON-JavaScript Hijacking.
 *
 * @see https://github.com/faridibin/laravel-api-json-response/blob/master/README.md
 *
 * @author Farid Adam <me@faridibin.tech>
 */

trait HasApiResponse
{
    /**
     * Sets the api response.
     *
     * @return $this
     *
     * @final
     */
    private function setResponse(): static
    {
        $this->checkData();

        if ($this->hasMessage()) {
            $this->response['message'] = $this->getMessage();
        }

        if ($this->hasErrors()) {
            /*
            | Set status code to 400 if there are errors
            | only if it has not been specified before.
            */
            if ($this->hasErrors() && $this->getStatusCode() === Response::HTTP_OK) {
                $this->setStatusCode(Response::HTTP_BAD_REQUEST);
            }

            $this->response['errors'] = $this->getErrors();
        }

        $this->response = array_merge($this->response, [
            'data' => $this->getData(true),
            'success' => $this->isSuccessful(),
            'status' => $this->getStatus(),
            'status_code' => $this->getStatusCode(),
            'status_text' => $this->getStatusText()
        ]);

        return $this;
    }

    /**
     * Checks the data of the original content.
     *
     * @return void
     */
    public function checkData()
    {
        if (!empty($this->getData(true)) && $content = $this->getOriginalContent()) {
            /*
            | For each different type of content, we will do a different thing.
            | 1. Models: Model name is added on the data object.
            | 2. Collections: Collection items are added to the data object.
            | 3. Arrays: Array name is added on the data object.
            */
            if ($content instanceof \Illuminate\Database\Eloquent\Model) {
                $this->setData([
                    \Illuminate\Support\Str::snake(last(explode("\\", get_class($content)))) => $this->getData(true)
                ]);
            } else if ($content instanceof \Illuminate\Contracts\Support\Arrayable) {
                $arrayable = $content->toArray();

                if (config(LARAVEL_API_RESPONSE_CONFIG . '.resource_name', false)) {
                    $resource = \Illuminate\Support\Str::of(class_basename($content->items()[0]))->plural()->lower()->__toString();

                    $arrayable[$resource] = $arrayable['data'];
                    unset($arrayable['data']);
                }

                $this->setData($arrayable);
            } else if (is_array($content)) {
                $this->set($content);
            } else {
                $this->mergeData([$content]);
            }
        }
    }


    public function setHeaders(array $headers = [])
    {
        // TODO: implement setHeaders

        dd($headers, $this->headers);
        // if (
        //     $_response->headers->has(self::AUTH_HEADER) &&
        //     ($headerToken = $_response->headers->get(self::AUTH_HEADER))
        // ) {
        //     $headers[self::AUTH_HEADER] = $headerToken;
        //     $this->json()->setToken(null);
        // } elseif ($this->hasToken()) {
        //     $headers[self::AUTH_HEADER] = 'Bearer ' . $this->json()->getToken();
        //     $this->json()->setToken(null);
        // }

        return $this;
    }

    /**
     * Sets a data property on the data object
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->mergeData($key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Merges the data array with new data.
     *
     * @param array $data
     *
     * @return $this
     */
    public function mergeData(array $data)
    {
        $this->setData(array_merge($this->getData(true), $data));

        return $this;
    }

    /**
     * Merges the errors array with the json response errors.
     *
     * @param array $errors
     *
     * @return $this
     */
    public function mergeErrors(array $errors)
    {
        $this->errors = array_merge($this->errors, $errors);

        return $this;
    }

    /**
     * Removes an error from the error list.
     * If $key is empty all errors are removed.
     *
     * @param string $key
     *
     * @return $this
     */
    public function deleteError($key = null)
    {
        if ($key !== null) {
            unset($this->errors[$key]);
        } else {
            $this->setErrors([]);
        }

        return $this;
    }

    /**
     * Removes some data from the json data.
     * If $key is empty all data is removed.
     *
     * @param string $key
     *
     * @return $this
     */
    public function deleteData($key = null)
    {
        if ($key !== null) {
            unset($this->data[$key]);
        } else {
            $this->setData([]);
        }

        return $this;
    }

    /**
     * Checks if message is empty or null.
     *
     * @return bool
     */
    public function hasMessage()
    {
        return is_string($this->message) && $this->message !== '';
    }

    /**
     * Checks if the response has errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * Checks if the response has a token.
     *
     * @return bool
     */
    public function hasToken()
    {
        return isset($this->token);
    }

    /**
     * Formats the response data with the specified uri case.
     * Supported cases are "camel", "snake", "spinal".
     *
     * @param string $case
     *
     * @return $this
     *
     * @final
     */
    public function format(string $case = LARAVEL_API_RESPONSE_URI_CASE)
    {
        // Sort response by key in ascending order.
        ksort($this->response);

        $this->response = format_with_uri_case($this->response, $case);

        return $this;
    }
}

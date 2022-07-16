<?php

namespace Faridibin\LaravelApiResponse\Traits;

use Illuminate\Http\Response;

/**
 * HasApiResponse is a set of reusable methods for handling API responses.
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
        // Set the response message.
        if ($this->hasMessage()) {
            $this->response['message'] = $this->getMessage();
        }

        // Set the response errors.
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

        // Set the response authorization token.
        if (
            config(LARAVEL_API_RESPONSE_CONFIG . '.token.include', false) &&
            config(LARAVEL_API_RESPONSE_CONFIG . '.token.scope') === 'response' &&
            $this->hasToken()
        ) {
            $this->response['authorization'] = $this->getToken();
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
     * @return $this
     */
    public function checkData()
    {
        if (!empty($this->getData(true)) && $content = $this->getOriginalContent()) {
            /*
            | For each different type of content, we will do a different thing.
            | 1. Models: Model name is added on the data object.
            | 2. Collections: Collection items are added to the data object.
            | 3. XML Document: Elements in an XML document added to the data object.
            | 4. Arrays: Array name is added on the data object.
            | 5. Other: The original content is added to the data object.
            */
            if ($content instanceof \Illuminate\Database\Eloquent\Model) {
                $this->setData([
                    \Illuminate\Support\Str::snake(last(explode("\\", get_class($content)))) => $this->getData(true)
                ]);
            } else if ($content instanceof \Illuminate\Contracts\Support\Arrayable) {
                if (config(LARAVEL_API_RESPONSE_CONFIG . '.resource_name', false)) {
                    $resource = \Illuminate\Support\Str::of(class_basename($content->first()))->plural()->lower()->__toString();

                    if ($content instanceof \Illuminate\Database\Eloquent\Collection) {
                        $this->setData([$resource => $content->toArray()]);
                    }

                    if ($content instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                        $arrayable = $content->toArray();
                        $arrayable[$resource] = $arrayable['data'];

                        unset($arrayable['data']);

                        $this->setData($arrayable);
                    }
                }
            } else if ($content instanceof \SimpleXMLElement) {
                $this->set($content->asXML());
            } else if (is_array($content)) {
                $this->set($content);
            } else {
                $this->mergeData([$content]);
            }
        }

        return $this;
    }

    /**
     * Checks if the request has a token.
     * If a token is found, it will be added to the response.
     *
     * @param string $authorization
     *
     * @return void
     */
    public function checkToken(string $authorization = null)
    {
        if (!empty($authorization)) {
            list($scheme, $token) = explode(' ', $authorization);

            $this->setToken($token, $scheme);
        }
    }

    /**
     * Sets the headers.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers = [])
    {
        // Set content type of response.
        switch (config(LARAVEL_API_RESPONSE_CONFIG . '.data_format', LARAVEL_API_RESPONSE_FORMAT)) {
            case 'xml':
                $this->headers->set('Content-Type', ['application/xml', 'text/xml']);
                break;

            case 'yml':
                $this->headers->set('Content-Type', ['application/x-yaml', 'text/x-yaml']);
                break;

            default:
                $this->headers->set('Content-Type', 'application/json');
                break;
        }

        // Set authorization.
        if (
            config(LARAVEL_API_RESPONSE_CONFIG . '.token.include', false) &&
            config(LARAVEL_API_RESPONSE_CONFIG . '.token.scope') === 'header' &&
            $this->hasToken()
        ) {
            $this->headers->set('Authorization', $this->getToken(true));
        }

        // Set other headers.
        foreach ($headers as $key => $value) {
            $this->headers->set($key, $value);
        }

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
            $this->setData($key);
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
     * If key is empty all data is removed.
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
        return isset($this->authorization);
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

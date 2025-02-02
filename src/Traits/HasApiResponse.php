<?php

namespace Faridibin\LaravelApiResponse\Traits;

use Illuminate\Http\Response;

trait HasApiResponse
{
    /**
     * The response data.
     * 
     * @var array
     */
    protected array $data = [];

    /**
     * The response errors.
     * 
     * @var array
     */
    protected array $errors = [];

    /**
     * The response headers.
     * 
     * @var array
     */
    protected array $headers = [];

    /**
     * The response status code.
     * 
     * @var int
     */
    protected int $statusCode = 200;

    /**
     * The response message.
     * 
     * @var string|null
     */
    protected ?string $message = null;

    /**
     * Set the response headers.
     * 
     * @param array $headers
     * @return static
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the response headers.
     * 
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Sets the HTTP status code to be used for the response.
     *
     * @param  int  $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get the response status code.
     * 
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set the response message.
     * 
     * @param string $message
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set a data value.
     * 
     * @param mixed $key
     * @param mixed $value
     * @return static
     */
    public function set(mixed $key, mixed $value = null): static
    {
        match (true) {
            is_array($key) => $this->data = array_merge($this->data, $key),
            default => $this->data[$key] = $value
        };

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Merge the response errors.
     * 
     * @param array $errors
     * @return static
     */
    public function mergeErrors(array $errors): static
    {
        $this->errors = array_merge($this->errors, $errors);

        return $this;
    }

    public function mergeData(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Is response successful?
     *
     * @final
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Get the response data.
     * 
     * @return array
     */
    protected function getResponse(): array
    {
        // TODO: Implement case and return type handling.

        $response = [
            'data' => $this->getData(),
            'message' => $this->message,
            'errors' => $this->errors,
            'success' => $this->isSuccessful(),
            'status' => $this->statusCode === 200 ? 'success' : 'error',
            'status_code' => $this->statusCode,
            'status_text' => Response::$statusTexts[$this->statusCode] ?? 'unknown status'
        ];

        return array_filter($response, fn($value) => !is_null($value));
    }
}

<?php

namespace Faridibin\LaravelApiResponse\Support;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;

class Yaml
{
    /**
     * The XML namespace prefix.
     *
     * @var array
     */
    protected $options;

    /**
     * Create a new YAML instance.
     *
     * @param  mixed  $data
     * @param  array  $options
     *
     * @return void
     */
    public function __construct($data, array $options)
    {
        $this->data = isset($options['root']) ? [$options['root'] =>  $data] : $data;
        $this->options = $options;
    }

    /**
     * Statically parses YAML strings to array.
     *
     * @param  string  $string
     * @param  array  $options
     *
     * @return array
     */
    public static function parse(string $string, array $options = [])
    {
        $options = empty($options) ? config(LARAVEL_API_RESPONSE_CONFIG . '.yaml', LARAVEL_API_RESPONSE_YAML_CONFIG) : $options;

        return (new static($string, $options))->toArray();
    }

    /**
     * Statically dump array to YAML string.
     *
     * @param  array  $data
     * @param  string  $options
     *
     * @return string
     */
    public static function convert(array $data, array $options)
    {
        return (new static($data, $options))->toYaml();
    }

    /**
     * Parse YAML strings to array.
     *
     * @return array
     */
    public function toArray()
    {
        $parser = new Parser();
        ['flags' => $flags] = $this->options;

        try {
            return $parser->parse($this->data, $flags);
        } catch (ParseException $e) {
            throw new ParseException($e->getMessage());
        }
    }

    /**
     * Convert the YAML document to a string.
     *
     * @return string
     */
    public function toYaml()
    {
        $dumper = new Dumper();
        ['inline' => $inline, 'indent' => $indent, 'flags' => $flags] = $this->options;

        return $dumper->dump($this->data, $inline, $indent, $flags);
    }
}

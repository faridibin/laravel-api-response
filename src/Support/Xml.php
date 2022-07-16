<?php

namespace Faridibin\LaravelApiResponse\Support;

use DOMElement;

class Xml
{
    /**
     * The XML document.
     *
     * @var \DOMDocument
     */
    protected $document;

    /**
     * The root XML element.
     *
     * @var \DOMElement
     */
    protected $root;

    /**
     * The XML namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The XML namespace prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Create a new XML instance.
     *
     * @param  array  $data
     * @param  string  $options
     *
     * @return void
     */
    public function __construct($data, $options)
    {
        ['root' => $root, 'namespace' => $namespace, 'prefix' => $prefix, 'encoding' => $encoding, 'version' => $version] = $options;

        $this->document = new \DOMDocument($version, $encoding);
        $this->root = $this->document->createElement($root);
        $this->document->appendChild($this->root);

        $this->namespace = $namespace;
        $this->prefix = $prefix;

        // Build the XML document.
        $this->build($data, $this->root, $this->namespace, $this->prefix);
    }

    /**
     * Convert the XML document to a string.
     *
     * @param  array  $data
     * @param  string  $options
     *
     * @return string
     */
    public static  function convert($data, $options)
    {
        return (new static($data, $options))->toXml();
    }

    /**
     * Convert the XML document to a string.
     *
     * @return string
     */
    public function toXml()
    {
        return $this->document->saveXML();
    }

    /**
     * Build the XML document.
     *
     * @param array $data
     * @param DOMElement $root
     * @param string $namespace
     * @param string $prefix
     *
     * @return void
     */
    private function build(array $data, DOMElement $root, string $namespace = null, string $prefix = null)
    {
        if ($namespace) {
            $root->setAttribute($prefix, $namespace);
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_int($key)) {
                    $key = (!in_array($root->nodeName, LARAVEL_API_RESPONSE_XML_KEYWORDS)) ? \Illuminate\Support\Str::singular($root->nodeName) : 'item';
                }

                $tab = $root->appendChild($this->document->createElement((string) $key));

                $this->build($value, $tab);
            } else {
                $root->appendChild($this->document->createElement($key, $value));
            }
        }
    }
}

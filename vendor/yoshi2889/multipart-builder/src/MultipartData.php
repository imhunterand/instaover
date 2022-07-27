<?php
/**
 * Copyright 2017 NanoSector
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace MultipartBuilder;

class MultipartData
{
    /**
     * @var string
     */
    protected $name = '';
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var string
     */
    protected $contents = '';
    /**
     * @var string
     */
    protected $filename = '';

    /**
     * MultipartData constructor.
     *
     * @param string $name
     * @param array $headers
     * @param string $contents
     * @param string $filename
     */
    public function __construct(string $name, string $contents, string $filename = '', array $headers = [])
    {
        $this->name = $name;
        $this->headers = $headers;
        $this->contents = $contents;
        $this->filename = $filename;
    }


    /**
     * @return string
     */
    public function build(): string
    {
        $this->prepareHeaders();
        $string = $this->buildHeaders();
        $string .= "\r\n";
        $string .= $this->getContents();
        $string .= "\r\n";
        return $string;
    }

    protected function prepareHeaders()
    {
        if (!isset($this->headers['Content-Disposition']))
            $this->headers['Content-Disposition'] = 'form-data; name="' . $this->getName() . '"'
                . (!empty($this->getFilename()) ? '; filename="' . $this->getFilename() . '"' : '');

        if (!isset($this->headers['Content-Length']))
            $this->headers['Content-Length'] = strlen($this->getContents());

        if (!empty($this->getFilename()) && !isset($this->headers['Content-Type']) && ($mimeType = ExtensionHelper::getMimetype($this->getFilename())))
            $this->headers['Content-Type'] = $mimeType;
    }

    /**
     * @return string
     */
    public function buildHeaders(): string
    {
        $string = '';
        foreach ($this->headers as $header => $value)
            $string .= $header . ': ' . $value . "\r\n";
            
        return $string;
    }
    
    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     */
    public function setContents(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
<?php
/**
 * Copyright 2017 NanoSector
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace MultipartBuilder;

class Builder
{
    /**
     * @var DataCollection
     */
    protected $dataCollection;
    
    /**
     * @var string
     */
    protected $boundary;

    public function __construct(array $initialValues = [], string $boundary = '')
    {
        $this->dataCollection = new DataCollection($initialValues);
        $this->boundary = $boundary;
    }

    public function append(MultipartData $data)
    {
        $this->getDataCollection()->append($data);
    }

    /**
     * @return string|false false if no data was generated.
     */
    public function buildAll()
    {
        if (empty($this->boundary))
            $this->boundary = $this->generateBoundary();
        
        $string = '';
        
        /** @var MultipartData $multipartData */
        foreach ((array) $this->dataCollection as $multipartData)
        {
            $string .= '--' . $this->boundary . "\r\n";
            $string .= $multipartData->build();
        }
        
        if (empty($string))
            return false;
        
        $string .= '--' . $this->boundary . '--' . "\r\n";
        
        return $string;
    }

    public function generateBoundary(): string
    {
        return uniqid('', true);
    }

    /**
     * @return string
     */
    public function getBoundary(): string
    {
        return $this->boundary;
    }

    /**
     * @return DataCollection
     */
    public function getDataCollection(): DataCollection
    {
        return $this->dataCollection;
    }
}
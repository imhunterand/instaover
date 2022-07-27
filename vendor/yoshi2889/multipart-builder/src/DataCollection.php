<?php
/**
 * Created by PhpStorm.
 * User: rick2
 * Date: 27-8-2017
 * Time: 20:45
 */

namespace MultipartBuilder;

use Yoshi2889\Collections\Collection;

class DataCollection extends Collection
{
    public function __construct(array $initialValues = [])
    {
        parent::__construct(function ($value)
        {
            return $value instanceof MultipartData;
        }, $initialValues);
    }
}
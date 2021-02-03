<?php

namespace App\Interfaces\XmlParser;


/**
 * Class XmlParser
 * @package App\Components\XmlParser
 */
interface XmlParserInterface
{
    /**
     * @param string $fileName
     * @param callable $save
     */
    public function parse(string $fileName, callable $save);
}
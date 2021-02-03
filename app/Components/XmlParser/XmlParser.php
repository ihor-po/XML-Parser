<?php

namespace App\Components\XmlParser;

use App\Interfaces\XmlParser\XmlParserInterface;
use XMLReader;

/**
 * Class XmlParser
 * @package App\Components\XmlParser
 */
class XmlParser implements XmlParserInterface
{
    private const NODE_RECORD = 'RECORD';

    /** @var XMLReader  */
    private $xmlReader;

    /** @var int  */
    private $chunkSize;

    /** @var string  */
    private $encoding;

    /** @var array */
    private $chunk;

    /** @var int */
    private $chunkCounter;

    /**
     * XmlParser constructor.
     * @param int $chunkSize
     * @param string $encoding
     */
    public function __construct(int $chunkSize, string $encoding)
    {
        $this->chunkSize = $chunkSize;
        $this->encoding = $encoding;
        $this->xmlReader = new XMLReader();
    }

    /**
     * @param string $fileName
     * @param callable $save
     */
    public function parse(string $fileName, callable $save)
    {
        $this->resetChunk();
        $this->xmlReader->open($fileName, $this->encoding);
        $this->nodeRead($save);
        $this->xmlReader->close();

        if (count($this->chunk)) {
            $save($this->chunk);
            $this->resetChunk();
        }
    }

    /**
     * @param callable $save
     */
    private function nodeRead(callable $save)
    {
        while($this->xmlReader->read()) {
            $node = $this->xmlReader->expand();
            if (!$this->isNodeRecord($node)) {
                continue;
            }

            $nodeData = $this->getNodChildrenData($node->childNodes);

            if (count($nodeData)) {
                $this->chunk[] = $nodeData;
                $this->chunkCounter++;
            }

            if ($this->chunkCounter >= $this->chunkSize) {
                $save($this->chunk);
                $this->resetChunk();
            }
        }
    }

    /**
     * @param $node
     * @return bool
     */
    private function isNodeRecord($node): bool
    {
        return $node->localName === static::NODE_RECORD;
    }

    /**
     * @param $children
     * @return array
     */
    private function getNodChildrenData($children): array
    {
        $childrenArray = [];
        foreach ($children as $child) {
            $value = $child->nodeName === 'FOUNDERS'
                ? $this->getSubChildrenData($child->childNodes)
                : $child->textContent;

            $value = str_replace("\\","\\\\", $value);
            $value = str_replace("'","\'", $value);

            $childrenArray[] = "'" . $value . "'";
        }
        return $childrenArray;
    }

    /**
     * @param $subChildren
     * @return string
     */
    private function getSubChildrenData($subChildren): string
    {
        $subChildrenArray = [];
        foreach ($subChildren as $child) {
            $subChildrenArray[] = $child->textContent;
        }

        return implode(' || ', $subChildrenArray);
    }

    /**  */
    private function resetChunk()
    {
        $this->chunk = [];
        $this->chunkCounter = 0;
    }
}
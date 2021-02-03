<?php


namespace App;


use App\Components\DataBase\DataBaseComponent;
use App\Components\XmlParser\XmlParser;

/**
 * Class Application
 * @package App
 */
class Application
{
    /**
     * @param string $fileName
     * @param int $chunkSize
     * @param string $encoding
     */
    public function start(string $fileName, int $chunkSize, string $encoding)
    {
        $dbComponent = new DataBaseComponent();
        $dbComponent->prepare();

        $saveChunk = function (array $chunk) use($dbComponent){
            $dbComponent->insertChunk($chunk);
        };

        $xmlParser = new XmlParser($chunkSize, $encoding);
        $xmlParser->parse($fileName, $saveChunk);
    }
}
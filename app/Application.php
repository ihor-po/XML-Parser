<?php


namespace App;


use App\Components\AddressParser\AddressParser;
use App\Components\DataBase\DataBaseComponent;
use App\Components\XmlParser\XmlParser;
use Exception;
use XMLReader;


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
     * @throws Exception
     */
    public function start(string $fileName, int $chunkSize, string $encoding)
    {
        $this->testXmlParser($fileName, $chunkSize, $encoding);
        $this->testAddressParser('Україна, 00000, Обласна обл., місто Чудове, ПРОВУЛОК ФРАНКА, будинок 44, квартира 344');
    }

    /**
     * @param string $fileName
     * @param int $chunkSize
     * @param string $encoding
     * @throws Exception
     */
    private function testXmlParser(string $fileName, int $chunkSize, string $encoding)
    {
        $dbComponent = new DataBaseComponent();
        $dbComponent->prepare();

        $saveChunk = function (array $chunk) use($dbComponent){
            $dbComponent->insertChunk($chunk);
        };

        $xmlParser = new XmlParser($chunkSize, $encoding, new XMLReader());
        $xmlParser->parse($fileName, $saveChunk);
    }

    /**
     * @param string $address
     */
    private function testAddressParser(string $address)
    {
        $addressParser = new AddressParser();
        $parts = $addressParser->parse($address);
        print_r(['parts' => $parts]);
    }
}
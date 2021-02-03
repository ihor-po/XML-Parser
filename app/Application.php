<?php


namespace App;


use App\Components\AddressParser\AddressParser;
use App\Components\DataBase\DataBaseComponent;
use App\Components\XmlParser\XmlParser;
use Exception;


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
//        $this->testXmlParser($fileName, $chunkSize, $encoding);
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

        $xmlParser = new XmlParser($chunkSize, $encoding);
        $xmlParser->parse($fileName, $saveChunk);
    }

    /**
     *
     */
    private function testAddressParser(string $address)
    {
//        $addresses = file_get_contents('./test.json');
//        $addresses = json_decode($addresses, true);
//        $addresses = $addresses['data'];

//        foreach ($addresses as $address) {
            $addressParser = new AddressParser();
//            $addressParser->parse($address['ADDRESS']);
            $parts = $addressParser->parse($address);
            print_r(['parts' => $parts]);
//        }

    }
}
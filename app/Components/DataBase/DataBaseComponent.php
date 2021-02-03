<?php


namespace App\Components\DataBase;


use Exception;
use PDO;

/**
 * Class DataBaseComponent
 * @package App\Components\DataBase
 */
class DataBaseComponent
{
    private $db;

    /**
     * @throws Exception
     */
    public function connect()
    {
        if ($this->db === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';';
                $this->db = new PDO($dsn, DB_USER, DB_PASS);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(Exception $exception) {
                throw $exception;
            }
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function prepare(): bool
    {
        $this->connect();
        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_TABLE . "`(
`ID` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
`NAME` varchar(255) NOT NULL,
`SHORT_NAME` varchar(200) NOT NULL,
`EDRPOU` varchar(10),
`ADDRESS` TEXT,
`KVED` varchar(255),
`BOSS` varchar(255),
`BENEFICIARIES` varchar(1),
`FOUNDERS` TEXT,
`STAN` varchar(120)) 
CHARACTER SET utf8 COLLATE utf8_general_ci";

        return (bool)$this->db->exec($sql);
    }

    /**
     * @param array $chunk
     * @return bool
     * @throws Exception
     */
    public function insertChunk(array $chunk): bool
    {
        $preparedChunk = $this->prepareChunk($chunk);

        $this->connect();
        $sql = 'INSERT INTO `' . DB_TABLE . '` ';
        $sql .= '(`NAME`, `SHORT_NAME`, `EDRPOU`, `ADDRESS`, `KVED`, `BOSS`, `BENEFICIARIES`, `FOUNDERS`, `STAN`) ';
        $sql .= 'VALUES ' . $preparedChunk . ';';

        return (bool)$this->db->exec($sql);
    }

    /**
     * @param array $chunk
     * @return string
     */
    private function prepareChunk(array $chunk): string
    {
        $preparedChunk = [];
        foreach($chunk as $item) {
            $preparedChunk[] = '(' . implode(',', $item) . ')';
        }

        return implode(',', $preparedChunk);
    }

}
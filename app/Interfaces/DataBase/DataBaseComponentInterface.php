<?php

namespace App\Interfaces\DataBase;


use Exception;

/**
 * Class DataBaseComponent
 * @package App\Components\DataBase
 */
interface DataBaseComponentInterface
{
    /**
     * @throws Exception
     */
    public function connect();

    /**
     * @return bool
     * @throws Exception
     */
    public function prepare(): bool;

    /**
     * @param array $chunk
     * @return bool
     * @throws Exception
     */
    public function insertChunk(array $chunk): bool;
}
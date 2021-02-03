<?php

use App\Application;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$app = new Application();
$app->start(FILE_NAME, CHUNK_SIZE, ENCODING);
<?php namespace app\config\db;

use PDO;

class Connect
{
    public object $connection;

    public function __construct() {
//        $dotenv = Dotenv::createImmutable('/data/');
//        $dotenv->load();
        $this->connection = new PDO('pgsql:host=localhost;port=5432;dbname=testdb', 'postgres', 'Serromanoff23');
    }
}
<?php

namespace Imams\SilsilahKeluargaApi\Config;

class Database
{
    public static function getConnection(): \PDO
    {
        $host = '127.0.0.1';
        $port = '3306';
        $db_name = 'silsilah_keluarga';
        $db_username = 'root';
        $db_password = '';

        return new \PDO('mysql:host='.$host.';dbname='.$db_name, $db_username, $db_password);
    }
}
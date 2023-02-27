<?php

// use PDO;

class Connection
{
    public function connect()
    {
        return new PDO("mysql:host=localhost;port=3306;dbname=admin_db","root","", [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]);
    }
}
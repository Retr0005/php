<?php

namespace Model;
use Util\Connection;

require 'Util/Connection.php';

class StudenteRepository {
    public static function listAllSur(): array {
        $pdo = Connection::getInstance();
        $sql = 'SELECT * FROM Studenti ORDER BY Cognome';
        $result = $pdo -> query($sql);
        return $result -> fetchAll();
    }
    public static function listAllName(): array {
        $pdo = Connection::getInstance();
        $sql = 'SELECT * FROM Studenti ORDER BY Nome';
        $result = $pdo -> query($sql);
        return $result -> fetchAll();
    }
    public static function listAllClass(): array {
        $pdo = Connection::getInstance();
        $sql = 'SELECT * FROM Studenti ORDER BY Classe';
        $result = $pdo -> query($sql);
        return $result -> fetchAll();
    }
}
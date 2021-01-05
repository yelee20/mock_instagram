<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "3.34.241.75";
        $DB_NAME = "instagram";
        $DB_USER = "sienna";
        $DB_PW = "Lyw991112@";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
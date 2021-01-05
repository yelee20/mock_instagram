<?php

function isValidUser($id, $pw){
    $pdo = pdoSqlConnect();
    $query = "select password as hash
              from User
              where personalContact = $id or userID = $id or email = $id;";

    $st = $pdo->prepare($query);
    $st->execute([$id,$pw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return password_verify($pw, $res[0]['hash']); //일치하면 1 아니면 0

}

function getUserIdxByID($ID)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT userIdx FROM User WHERE personalContact = $ID or userID = $ID or email = $ID;";

    $st = $pdo->prepare($query);
    $st->execute([$ID]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['userIdx'];
}

function userBlocked($userIdx, $targetUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM BlockedUser WHERE blockedUserIdx = ? AND userIdx = ?) AS exist;";


    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $targetUserIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}
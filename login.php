<?php

$db = new SQLite3('db.db');

/**
 * Permet d'ajouter un code de sécurité pour se connecter
 */
function addCodeHash(){
    global $db;
    $password = password_hash('', PASSWORD_DEFAULT);
 
    $q = $db->prepare('INSERT INTO user (code) VALUES (:code)');
    $q->bindValue('code', $password);
    $res = $q->execute();

    if ($res) {
        echo "Création de compte réussie";
    }
}

/**
 * Vérifier si le code rentré est correct
 */
function verifyCode($code){
    global $db;
 
    $res = $db->querySingle('SELECT code FROM user WHERE id = 1');
    return password_verify($code, $res);
}
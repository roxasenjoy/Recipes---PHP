<?php

$db = new SQLite3('db-pp.db');

/**
 * Permet d'ajouter un code de sécurité pour se connecter
 */
function addCodeHash(){
    global $db;
    $password = password_hash('andrew', PASSWORD_DEFAULT);
 
    $q = $db->prepare('INSERT INTO code (code) VALUES (:code)');
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
 
    $res = $db->querySingle('SELECT code FROM code WHERE id = 8');
    return password_verify($code, $res);
}


function authenticateUser($username, $password){

    global $db;

    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindValue(':email', $username);
    $user = $stmt->execute();

    while ($row = $user->fetchArray()) {
        $userInfo = $row;
    }

    // vérifie si un utilisateur a été trouvé et si le mot de passe est correct
    if ($userInfo && password_verify($password, $userInfo['password'])) {
        // connecte l'utilisateur en stockant son ID dans la session
        $_SESSION['user_id'] = $userInfo['id'];
        $_SESSION['first_name'] = $userInfo['first_name'];
        $_SESSION['last_name'] = $userInfo['last_name'];
        return true;
    } else {
        return false;
    }
}

function createAccount($firstName, $lastName, $email, $password, $verifyPassword){
    global $db;

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($verifyPassword)) {
        return 'Tous les champs doivent être remplis.';
    }

    // check if the passwords match
    if ($password !== $verifyPassword) {
        return 'Les mots de passe ne correspondent pas.';
    }

    // insert the new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)');
    $stmt->bindValue(':firstName', $firstName);
    $stmt->bindValue(':lastName', $lastName);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $hashedPassword);
    $result = $stmt->execute();
    
    if ($result) {
        // log in the user by storing their ID in the session
        $_SESSION['user_id'] = $db->lastInsertRowId();
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        return true;
    } else {
        return "Une erreur s'est produite lors de la création de votre compte.";
    }
}

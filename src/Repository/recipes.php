<?php

$db = new SQLite3('db-pp.db');

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

function verifyCode($code){
    global $db;
 
    $res = $db->querySingle('SELECT code FROM user WHERE id = 1');
    return password_verify($code, $res);
}

function getRecipes() {
    global $db;

    $recettes = [];
    $results = $db->query('SELECT * FROM recette ORDER BY id desc');
    while ($row = $results->fetchArray()) {
        array_push($recettes, $row);
    }

    return $recettes;
}

function getIngredients($id) {
    global $db;

    $ingredients = [];
    $results = $db->query('SELECT ingredients.* 
                            FROM recette 
                            JOIN ingredients ON recette.id = ingredients.id_recette 
                            WHERE recette.id = ' . $id);
    while ($row = $results->fetchArray()) {
        array_push($ingredients, $row);
    }
    return $ingredients;
}

function getRecipeById($id) {
    global $db;

    $recipe_by_id = [];
    $results = $db->query('SELECT * FROM recette WHERE recette.id = ' . $id);
    while ($row = $results->fetchArray()) {
        array_push($recipe_by_id, $row);
    }
    return $recipe_by_id;
}

function getInstructions($id) {
    global $db;

    $instructions = [];
    $results = $db->query('SELECT instructions.* 
                            FROM instructions 
                            JOIN recette ON recette.id = instructions.id_recette 
                            WHERE recette.id = ' . $id);
    while ($row = $results->fetchArray()) {
        array_push($instructions, $row);
    }
    return $instructions;
}
?>

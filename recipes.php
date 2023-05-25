<?php

$db = new SQLite3('db.db');


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

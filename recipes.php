<?php

$db = new SQLite3('db.db');

function getRecipes($time = '') {
    global $db;

    $recettes = [];
    
    $query = 'SELECT * FROM recette';
    $query = addTime($query, $time);
    $query .= ' ORDER BY id DESC';

    $results = $db->query($query);

    while ($row = $results->fetchArray()) {
        array_push($recettes, $row);
    }
    return $recettes;
}

function addTime($query, $time){
    if (!empty($time)) {
        $query .= ' WHERE time_total IN ('. $time . ')';
    }

    return $query;
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

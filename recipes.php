<?php

$db = new SQLite3('db-pp.db');

function getRecipes($time = '', $researchText ='') {
    global $db;

    $recettes = [];
    
    $query = 'SELECT * FROM recette';
    $whereClauses = [];
    $query = addTime($query, $time, $whereClauses);
    $query = addResearchText($query, $researchText, $whereClauses);

    if (!empty($whereClauses)) {
        $query .= ' WHERE ' . implode(' AND ', $whereClauses);
    }
    
    $query .= ' ORDER BY id DESC';

    $results = $db->query($query);

    while ($row = $results->fetchArray()) {
        array_push($recettes, $row);
    }
    return $recettes;
}

function addResearchText($query, $researchText, &$whereClauses){
    if (!empty($researchText)) {
        $whereClauses[] = 'name LIKE "%' . SQLite3::escapeString($researchText) . '%"';
    }
    return $query;
}

function addTime($query, $time, &$whereClauses){
    if (!empty($time)) {
        $whereClauses[] = 'time_total IN ('. SQLite3::escapeString($time) . ')';
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

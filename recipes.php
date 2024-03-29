<?php

$db = new SQLite3('db-test.db');

function getRecipes($time = '', $researchText ='', $userId = '', $canUserRecipesAddedFilter = false) {
    global $db;

    /* Obtenir la liste des recettes ajoutées par l'utilisateur */
    $all_user_recipes = $db->prepare('SELECT recipe_id FROM user_recipes WHERE user_id = :user_id');
    $all_user_recipes->bindValue(':user_id', $userId);
    $result = $all_user_recipes->execute();

    $recipesAdded = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($recipesAdded, $row['recipe_id']);
    }

    $recettes = [];

    $query = 'SELECT * FROM recette';
    $whereClauses = [];
    $query = addTime($query, $time, $whereClauses);
    $query = addResearchText($query, $researchText, $whereClauses);
    $orderStatement = addRecipesAdded($recipesAdded, $canUserRecipesAddedFilter);

    if (!empty($whereClauses)) {
        $query .= ' WHERE ' . implode(' AND ', $whereClauses);
    }
    
    if (!empty($recipesAdded) && $canUserRecipesAddedFilter === 'true') {
        $query .= ' ORDER BY ' . $orderStatement . ', id DESC';
    } else {
        $query .= ' ORDER BY id DESC';
    }
    
    $results = $db->query($query);

    while ($row = $results->fetchArray()) {
        array_push($recettes, $row);
    }

    return $recettes;
}

function addRecipesAdded($recipesAdded, $canUserRecipesAddedFilter){

    $caseStatement = 'CASE id ';

    if (!empty($recipesAdded) && $canUserRecipesAddedFilter === 'true') {
        foreach($recipesAdded as $index => $id) {
            $caseStatement .= 'WHEN ' . $id . ' THEN ' . $index . ' ';
        }

        $caseStatement .= 'ELSE ' . count($recipesAdded) . ' END';
    }
    else {
        $caseStatement = 'id';
    }

    return $caseStatement;
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

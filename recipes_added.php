<?php

/**
 * Ajoute à la table user_recipes, la liste des recettes que l'utilisateur a sélectionné.
 */

session_start();
$db = new SQLite3('db-test.db');
header('Content-Type: application/json');

if (isset($_GET['functionToUse'])) {
    $functionToUse = $_GET['functionToUse'];

    switch ($functionToUse) {
        case 'addOrRemoveRecipe':
            addOrRemoveRecipe($db);
            break;

        case 'getRecipesAddedByUser':
            getRecipesAddedByUser($db);
            break;

        case 'addRecipesInCart':
            addRecipesInCart($db);
            break;

        // Vous pouvez ajouter d'autres fonctions ici si nécessaire
        default:
            echo 'Function not found or not specified.';
            break;
    }
}

function getRecipesAddedByUser($db){

    $all_user_recipes = $db->prepare('SELECT recipe_id FROM user_recipes WHERE user_id = :user_id');
    $all_user_recipes->bindValue(':user_id', $_SESSION['user_id']);
    $result = $all_user_recipes->execute();

    $listRecipes = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($listRecipes, $row['recipe_id']);
    }
    
    echo json_encode(array(
        'list_recipes' => $listRecipes
    ));

}

function addOrRemoveRecipe($db){
    
    $selectedRecipeId = $_GET['selectedRecipeId'];
    if($selectedRecipeId){

        $user_already_add_selectedRecipeId = $db->prepare('SELECT id FROM user_recipes WHERE user_id = :user_id AND recipe_id = :selectedRecipeId');
        $user_already_add_selectedRecipeId->bindValue(':user_id', $_SESSION['user_id']);
        $user_already_add_selectedRecipeId->bindValue(':selectedRecipeId', $selectedRecipeId);
        $result = $user_already_add_selectedRecipeId->execute();

        if ($result !== false) {
            $row = $result->fetchArray(SQLITE3_ASSOC);
            if ($row) {
                $q = $db->prepare('DELETE FROM user_recipes WHERE id = :id');
                $q->bindValue(':id', $row['id']); 
                $q->execute();
                echo 'false';
            } else {
                $q = $db->prepare('INSERT INTO user_recipes (user_id, recipe_id) VALUES (:user, :selectedRecipeId)');
                $q->bindValue(':user', $_SESSION['user_id']);  
                $q->bindValue(':selectedRecipeId', $selectedRecipeId);  
                $q->execute();

                echo 'true';
            }
        } else {
            echo "Query execution failed \n";
        }
    }
}

function addRecipesInCart(){

    global $db;
    
    $recipeId = $db->prepare(' SELECT ingredients.name as ingredient FROM user_recipes JOIN ingredients ON user_recipes.recipe_id = ingredients.id_recette WHERE user_recipes.user_id = :user_id');
    $recipeId->bindValue(':user_id', $_SESSION['user_id']);
    $result = $recipeId->execute();

    $listIngredients = [];
    if ($result !== false) {

        // Setup du tableau avec la liste des recettes ajoutées par l'utilisateur
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            array_push($listIngredients, $row['ingredient']);
        }
        
        echo json_encode(array(
            'list_ingredients' => $listIngredients
        ));

    } else {
        echo "Query execution failed \n";
    }
}



<?php

/**
 * Permet d'obtenir tous les détails relatifs à la modal dès qu'on clique sur une recette
 */

require 'recipes.php';

header('Content-Type: application/json');

$id = $_GET['id'];

$ingredients = getIngredients($id);
$recettes = getRecipeById($id);
$instructions = getInstructions($id);


echo json_encode(array(
    "ingredients" => $ingredients,
    "recettes" => $recettes,
    "instructions" => $instructions
));
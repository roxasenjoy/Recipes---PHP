<?php

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
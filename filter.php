<?php
require 'recipes.php';

$time = $_GET['time'];
$research = $_GET['research'];
$recipesAdded = $_GET['recipesAdded'];
$canUserRecipesAddedFilter = $_GET['canUserRecipesAddedFilter'];


$recipes = getRecipes($time, $research, $recipesAdded, $canUserRecipesAddedFilter);


echo json_encode($recipes);

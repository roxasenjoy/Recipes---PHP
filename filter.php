<?php
require 'recipes.php';

session_start();

$time = $_GET['time'];
$research = $_GET['research'];
$userId = $_SESSION['user_id'];
$canUserRecipesAddedFilter = $_GET['canUserRecipesAddedFilter'];

$recipes = getRecipes($time, $research, $userId, $canUserRecipesAddedFilter);

echo json_encode($recipes);

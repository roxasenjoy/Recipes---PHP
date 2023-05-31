<?php
require 'recipes.php';

$time = $_GET['time'];
$research = $_GET['research'];

$recipes = getRecipes($time, $research);

echo json_encode($recipes);

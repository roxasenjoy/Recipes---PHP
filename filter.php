<?php
require 'recipes.php';

$time = $_GET['time'];

$recipes = getRecipes($time);

echo json_encode($recipes);

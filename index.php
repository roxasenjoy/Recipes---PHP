<?php
require 'recipes.php'; 
require 'login.php'; 
session_unset();
session_start();

if (isset($_POST['code'])) {
    $isValid = verifyCode($_POST['code']);
    if ($isValid) {
        $_SESSION['is_authenticated'] = true;
    } else {
        $errorMsg = "Mauvais code";
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Recettes perso</title>

        <meta name="description" content="Free Web tutorials">
        <meta name="keywords" content="HTML,CSS,JavaScript">
        <meta name="author" content="John Doe">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- jQuery et Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


        <link rel="stylesheet" href="index.css" type="text/css">
        <link rel="stylesheet" href="login-form.css" type="text/css">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

        <script src="https://kit.fontawesome.com/128f69e9e2.js"></script>


        
    </head>

    <body>

        <?php if (isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated']) : ?>

            <div class="global_container">

                <!-- <h1> Des idées de recettes  </h1> -->

                <div class="pos-f-t">
                   
                    <nav class="filtreBtn-container">
                        <button class="navbar-toggler filtreBtn" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa-solid fa-filter"></i> Filtres
                        </button>
                    </nav>

                    <div class="collapse" id="navbarToggleExternalContent">
                        <!-- Liste des filtres disponibles -->
                        <div class="filtre">
                            <!-- Filtre sur les calories -->

                            <!-- Barre de recherche -->

                            <!-- Filtre sur les ingrédients -->

                           

                            <!-- Filtre sur le temps de préparation -->
                            <div class="container-filtre">
                                <div class="select-btn">
                                    <span class="btn-text">Temps de préparation</span>
                                    <span class="arrow-dwn">
                                    <i class="fa-solid fa-chevron-down"></i>
                                    </span>
                                </div>

                                <ul class="list-items">
                                    <?php

                                        $timeList = [5,10,15,20,25,30,35,40,45,50,55,60,65,70];
                                        foreach ($timeList as $time) : 
                                    ?>

                                        <li class="item">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text"><?php echo $time; ?> min</span>
                                        </li>

                                    <?php endforeach; ?> 
                                </ul>
                            </div>

                              <!-- Filtre sur les recettes sélectionnées -->
                            <div class="containerRecipesAdded">
                                <input type="checkbox" id="canUserRecipesAddedFilter" name="canUserRecipesAddedFilter">
                                <label for="canUserRecipesAddedFilter">Afficher la liste de mes recettes</label>
                            </div>

                            <!-- Barre de recherche -->
                            <input type="text" class="research" placeholder="Rechercher une recette...">

                          
                            
                            <button class="researchBtn">
                                Filtrer
                            </button>
                        </div>
                    </div>
                </div> 
                      

                <!-- Liste contenants toutes les recettes disponibles -->
                <div class="recipes-list">
                    <?php 

                    foreach (getRecipes() as $row) : ?>
                        <a href="#" class="recipe-link" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#myModal" id="<?php echo $row['id']; ?>">
                            <div class="container">

                                <img src="<?php echo $row['image']; ?>" alt="" loading="lazy">
                                <p class="title"><?php echo $row['name']; ?></p>
                                <div>

                                    <?php if ($row['time_total']) : ?>
                                        <p class="kcalRecipes detailsContainer"> <i class="fa-solid fa-utensils"></i> <?php echo $row['kcal']; ?> kcal </p>
                                        <p class="timeCuisine detailsContainer"> <i class="fa-regular fa-hourglass-half"></i> <?php echo $row['time_total']; ?> min</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <!-- Fin de la liste des recettes -->

            </div>

          
            <!-- Modal -->
            <div class="modal left fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                <div class="closeModal" id="closeModal">
                    <i class="fa-solid fa-xmark"></i>
                </div>

                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="addCart" id="addCart" onClick="addRecipesToCart()">
                            Ajouter à la liste de mes recettes
                        </div>

                        <div class="modal-header">
                            <img src="" alt="" id="img-recipes">
                        </div>

                        <div class="modal-body">

                        </div>

                    </div><!-- modal-content -->
                </div><!-- modal-dialog -->
            </div><!-- modal -->

        <?php else: ?>

            <div class="login-container">
                <div class="login-page">
                    <div class="form">

                    <?php if (isset($errorMsg)) : ?>
                        <p class="error"><?php echo $errorMsg; ?></p>
                    <?php endif; ?>
                        <form class="login-form" method="POST">
                            <input type="text" id="code" name="code" placeholder="Rentrer le code de connexion">
                            <button type="submit" class="message">Accéder aux recettes</button>
                        </form>
      
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </body>

    <script src="index.js"></script>
    <script src="filter.js"></script>
    

</html>

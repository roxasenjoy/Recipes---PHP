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

                <h1> Des idées de recettes  </h1>

                <!-- Liste des filtres disponibles -->
                <div class="filtre">

                    <!-- Filtre sur les calories -->
                    <div class="container-filtre">
                        <div class="select-btn">
                            <span class="btn-text">Temps</span>
                            <span class="arrow-dwn">
                            <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </div>

                        <ul class="list-items">
                            <li class="item">
                                <span class="checkbox">
                                    <i class="fa-solid fa-check check-icon"></i>
                                </span>
                                <span class="item-text">French</span>
                            </li>
                            <li class="item">
                            <span class="checkbox">
                                <i class="fa-solid fa-check check-icon"></i>
                            </span>
                            <span class="item-text">English</span>
                            </li>
                            <li class="item">
                            <span class="checkbox">
                                <i class="fa-solid fa-check check-icon"></i>
                            </span>
                            <span class="item-text">Spanish</span>
                            </li>
                            <li class="item">
                            <span class="checkbox">
                                <i class="fa-solid fa-check check-icon"></i>
                            </span>
                            <span class="item-text">Chinese</span>
                            </li>
                            <li class="item">
                            <span class="checkbox">
                                <i class="fa-solid fa-check check-icon"></i>
                            </span>
                            <span class="item-text">Japanese</span>
                            </li>
                            <li class="item">
                            <span class="checkbox">
                                <i class="fa-solid fa-check check-icon"></i>
                            </span>
                            <span class="item-text">Korean</span>
                            </li>
                            <li class="item">
                            <span class="checkbox">
                                <i class="fa-solid fa-check check-icon"></i>
                            </span>
                            <span class="item-text">Italian</span>
                            </li>
                            <li class="item">
                            <span class="checkbox">
                                <i class="fa-solid fa-check check-icon"></i>
                            </span>
                            <span class="item-text">German</span>
                            </li>
                        </ul>
                    </div>



                    <!-- Barre de recherche -->

                    <!-- Filtre sur les ingrédients -->

                    <!-- Filtre sur le temps de préparation -->

                </div>


                <!-- Liste contenants toutes les recettes disponibles -->
                <div class="recipes-list" style="padding: 15px">
                    <?php 

                    foreach (getRecipes() as $row) : ?>
                        <a href="#" class="recipe-link" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#myModal">
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
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

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

<!DOCTYPE html>
<html>
    <head>
        <title>Recettes perso</title>
        <link rel="stylesheet" href="index.css" type="text/css">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

        <!-- jQuery et Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    </head>

    <body>

        <div class="recipes-list" style="padding: 15px">
            <?php 
            require 'recipes.php'; 
            foreach (getRecipes() as $row) : ?>
                <a href="#" class="recipe-link" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#myModal">
                    <div class="container">
                        <img src="<?php echo $row['image']; ?>" alt="" loading="lazy">
                        <p class="title"><?php echo $row['name']; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
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

    </body>

    <script src="index.js"></script>

</html>

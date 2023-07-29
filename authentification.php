<?php
session_start();

require 'login.php'; 

if(!isset($_SESSION['user_id'])){
    if (isset($_GET['type'])) {

        if($_GET['type'] == 'login' && isset($_GET['username']) && isset($_GET['password'])){
            /* Connexion à son compte */
            $isAuthenticated = authenticateUser($_GET['username'], $_GET['password']);
            if ($isAuthenticated) {
                header("Location: index.php"); // Redirect to your main page
                exit();
            } else {
                $errorMsg = "Les données ne sont pas correctes.";
            }
        }
    
        if($_GET['type'] == 'register' && isset($_GET['firstName']) && isset($_GET['lastName']) && isset($_GET['email']) && isset($_GET['password']) && isset($_GET['verifyPassword'])){
            /* Création du compte */
            $isRegistred = createAccount($_GET['firstName'], $_GET['lastName'], $_GET['email'], $_GET['password'], $_GET['verifyPassword']);
            if ($isRegistred === true) {
                header("Location: index.php"); // Redirect to your main page
                exit();
            } else {
                $errorMsg = $isRegistred;
            }
        }
    }
} else {
    header("Location: index.php");
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="authentification.css">
    <script src="https://kit.fontawesome.com/128f69e9e2.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>


    <div class="backRecipes">
        <a href="index.php" class="back">
            <i class="fa-solid fa-arrow-left"></i>
            Retourner aux recettes
        </a>
    </div>

    <div class="container__login ">

        <?php if($_GET['type'] === 'register'){ ?>
             <!-- Register -->
            <div class="connexion__elements" id="register">
                <h2>Création du compte</h2>
                
                <?php if (isset($errorMsg)) : ?>
                    <p class="error"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <div class="container__info">

                    

                    <form action="authentification.php" method="GET">
                        <input type="hidden" name="type" value="register">
                        <input type="text" id="firstName" placeholder="Prénom" name="firstName">
                        <input type="text" id="lastName" placeholder="Nom" name="lastName">
                        <input type="text" id="email" placeholder="Adresse email" name="email">
                        <input type="password" id="password" placeholder="Mot de passe" name="password">
                        <input type="password" id="passwordConfirm" placeholder="Confirmez votre mot de passe" name="verifyPassword">
                        <button type="submit">Créer son compte</button>
                    </form>

                    <div class="createAccount">
                        <a href="authentification.php?type=login" class="registerLink">Tu as un compte ? </a>
                    </div>
                </div>
            </div>
        <?php  } else if ($_GET['type'] === 'login'){ ?>

            <!-- Connexion -->
            <div class="connexion__elements" id="login">
                <h2>Connexion</h2>

                <?php if (isset($errorMsg)) : ?>
                    <p class="error"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <div class="container__info" >

                    <form method="GET" action="authentification.php">
                        <input type="hidden" name="type" value="login">
                        <input type="text" id="loginEmail" placeholder="Adresse email" name='username'>
                        <input type="password" id="loginPassword" placeholder="Mot de passe" name="password">
                        <button type="submit">Connexion</button>
                    </form>

                    <div class="createAccount">
                        <a href="authentification.php?type=register" class="registerLink" >Créer toi un compte, c'est gratuit </a>
                    </div>
                    
                </div>
            </div>
            <?php  }  ?>
    </div>





</body>
</html>
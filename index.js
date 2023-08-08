$(document).ready(function(){

    let selectedRecipeId = null; // Cette variable va stocker l'id de la recette sélectionnée
    addExistingRecipesWhenLogIn();

    $("#closeModal").click(function(){
        $("#myModal").modal('hide');
    });

    $(document).on("click", ".recipe-link", function(e){
        e.preventDefault();  // Empêche l'action par défaut (la navigation)
        
        let id = selectedRecipeId = $(this).data('id');  // Récupère l'ID de l'élément cliqué
        updateAddCartMessage(id);

        // Envoie une requête AJAX au serveur avec l'ID
        $.ajax({
            url: 'recipe_details.php',
            type: 'GET',
            data: { id: id },
            success: function(response) {

                var ingredients = response.ingredients;
                var recettes = response.recettes;
                var instructions = response.instructions;

                var modalBody = "";

                /* Changer l'image de la photo */
                $("#img-recipes").attr("src", recettes[0].image);
                
                modalBody += addName(recettes[0]);
                modalBody += addTimeAndKcal(recettes[0]);
                
                /* Liste des ingrédients */
                modalBody += addIngredients(ingredients, modalBody);

                /* Liste des instructions */
                for (var i = 0; i < instructions.length; i++) {
                
                    const step = instructions[i].step;
                    let texte = cutBeforeSecondUppercase(instructions[i].instructions);
                    texte = texte.replace(/[!?]/g, '.');

                    // Ajout de l'étape
                    modalBody += "<p class='step'> Étape n°" + step + "</p>";

                    let arrayTexte = texte.split('.');
                    for (let i = 0; i < arrayTexte.length; i++) {
                        if(arrayTexte[i]){
                            modalBody += "<p class='instructions'>- " + arrayTexte[i] + ".<br></p>"; // Ajoute une balise <br> à chaque phrase
                        }
                    }
                }

                $(".modal-body").html(modalBody);

                $('#myModal').modal('show');

              

                
            },
            error: function(error){
                console.log(error);
            }
        });
    });

   

    /**
     * Permet de remonter en haut de la page
     */
    var scrollButton = document.getElementById('scrollButton');
        scrollButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
    });

    
    /**
     * Stocker en base de données la recette que l'utilisateur vient de sélectionner.
     */
    $('#addCart').click(function(){

        $.ajax({
            url: 'recipes_added.php',
            type: 'GET',
            data: { 
                functionToUse: 'addOrRemoveRecipe',
                selectedRecipeId: selectedRecipeId 
            },
            success: function(response) {},
        });

    });
        


    /**
     * Pour toutes les recettes présentes dans la base de données, l'utilisateur se verra 
     */
    function updateAddCartMessage(id){
        const isChecked = $('#' + id).data('check');
        console.log(isChecked);

        if(isChecked === true){
            // Changer le texte + la couleur du fond - DEJA PRESENT
            $('#addCart').css("background-color", "#dc3545");
            $("#addCart").text("Supprimer de ma liste");
        } else {
            // Changer le texte + la couleur du fond - DEJA PRESENT
            $('#addCart').css("background-color", "#e1822d");
            $("#addCart").text("Ajouter à ma liste");
        }
        
    }

    /**
     * Ajoute le contour pour toutes les recettes qui sont déjà sélectionnées
     */
    function addExistingRecipesWhenLogIn(){


        // Ajout du contour de toutes les recettes disponible en base de données
        $.ajax({
            url: 'recipes_added.php',
            type: 'GET',
            data: { 
                functionToUse: 'getRecipesAddedByUser',
                selectedRecipeId: selectedRecipeId 
            },
            success: function(response) {

                const listRecipes = response['list_recipes'];

                // Return la liste de toutes les recettes sélectionnées.
                listRecipes.forEach(function(e){
                    $("#" + e).css('outline', 'thick solid rgb(225, 130, 45)');
                    $("#" + e).css('border-radius', '1rem');
                    $("#" + e).attr('data-check', true);
                });
            },
        }); 
    }
    
    /**
     * Faire sauter le texte poubelle au départ des phrases.
     * @param {*} str 
     * @returns 
     */
    function cutBeforeSecondUppercase(str) {
        let count = 0;
        let index = 0;

        for (let i = 0; i < str.length; i++) {
            if (str[i] === str[i].toUpperCase() && str[i] !== str[i].toLowerCase()) {
                count++;

                if (count === 2) {
                    index = i;
                    break;
                }
            }
        }

        return str.substring(index);
    }

    /**
     * Ajoute les ingrédients à la recette
     * @param {*} ingredients 
     * @returns 
     */
    function addIngredients(ingredients){
        let ingredientToAdd = '';
        ingredientToAdd += "<p class='title-ingredients'> Ingrédients pour 2 personnes : </p>";
        for (var i = 0; i < ingredients.length; i++) {
            ingredientToAdd += "<p class='ingredients'> - " + ingredients[i].name + "</p>";
        }

        return ingredientToAdd;
    }

    /**
     * Ajout le nom de la recette 
     * @param {*} recette 
     * @returns 
     */
    function addName(recette){
        return '<h2  class="recipe-title"> ' + recette.name + '</h2>';
    }

    /**
     * Ajout le temps et le nombre de kcal de la recette 
     * @param {*} recette 
     * @returns 
     */
    function addTimeAndKcal(recette){
        if(recette.kcal){
            return '  <h6  class="time"> \
                                Temps total ' + recette.time_total + ' min , \
                                Préparation ' + recette.time_preparation + ' min,  \
                                ' + recette.kcal + ' kcal \
                            </h6>';
        }

        return '';
    }
});

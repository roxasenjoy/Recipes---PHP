$(document).ready(function(){

    let selectedRecipeId = null; // Cette variable va stocker l'id de la recette sélectionnée
    addExistingRecipesWhenLogIn();
    setupRecipesAdded();

    $("#closeModal").click(function(){
        $("#myModal").modal('hide');
    });

    $(document).on("click", ".recipe-link", function(e){
        e.preventDefault();  // Empêche l'action par défaut (la navigation)
        
        let id = selectedRecipeId = $(this).data('id');  // Récupère l'ID de l'élément cliqué
        updateAddCart(id);

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

    /* Ajouter les éléments dans le localStorage */
    $("#addCart").click(function() {
        addRecipesToCart(selectedRecipeId);
    });


    // Find the element by its ID
    var scrollButton = document.getElementById('scrollButton');
        
    // Attach a click event listener to the element
    scrollButton.addEventListener('click', function() {
        // Scroll to the top of the page
        window.scrollTo({
        top: 0,
        behavior: 'smooth' // Add smooth scrolling effect
        });
    });


    function setupRecipesAdded(){
        if(localStorage.getItem('recipesAdded')){
            const recipesAdded = localStorage.getItem('recipesAdded');

            // Envoie une requête AJAX au serveur avec l'ID
            $.ajax({
                url: 'recipes_added.php',
                type: 'GET',
                data: { recipesAdded: recipesAdded },
                success: function(response) {
                    // console.log(response);
                    
                },
            });
        }

        
    }

    /**
     * Ajoute le contour pour toutes les recettes qui sont déjà sélectionnées
     */
    function addExistingRecipesWhenLogIn(){

        if(localStorage.getItem('recipesAdded')){
            JSON.parse(localStorage.getItem('recipesAdded')).forEach(function(e){
                $("#" + e).css('outline', 'thick solid rgb(225, 130, 45)');
                $("#" + e).css('border-radius', '1rem');
            });
        }
        
    }

    /**
     * Ajoute les recettes dans le localStorage
     * @param {*} selectedRecipeId 
     */
    function addRecipesToCart(selectedRecipeId){

        let recipesList = JSON.parse(localStorage.getItem('recipesAdded')) ?? [];

        if (recipesList.includes(selectedRecipeId)) {
            // Suppression de l'id déjà présent
            recipesList = recipesList.filter(id => id !== selectedRecipeId);
            updateBorderOfRecipesSelected(selectedRecipeId, false);
          } else {
            // Ajout de l'élément dans la liste
            recipesList.push(selectedRecipeId);
            updateBorderOfRecipesSelected(selectedRecipeId, true);
          }

        localStorage.setItem('recipesAdded', JSON.stringify(recipesList));
        updateAddCart(selectedRecipeId);
    }

    /**
     * Rajouter un contour pour les recettes qui sont sélectionnés.
     * @param {*} selectedRecipeId 
     */
    function updateBorderOfRecipesSelected(selectedRecipeId, display){

        if(display){
            $("[data-id='" + selectedRecipeId + "']").css('outline', 'thick solid rgb(225, 130, 45)');
            $("[data-id='" + selectedRecipeId + "']").css('border-radius', '1rem');
        } else {
            $("[data-id='" + selectedRecipeId + "']").css('outline', 'initial');
        }
    }

    /**
     * Modification du message d'ajout à la liste des recettes pour prévenir qu'il existe déjà.
     * @param {*} selectedRecipeId 
     */
    function updateAddCart(selectedRecipeId){
        const idIsInRecipesAdded = localStorage.getItem('recipesAdded') ? localStorage.getItem('recipesAdded').includes(selectedRecipeId) : false;

        if(idIsInRecipesAdded){
            // Changer le texte + la couleur du fond - DEJA PRESENT
            $('#addCart').css("background-color", "#dc3545");
            $("#addCart").text("Supprimer de la liste de mes recettes");
        } else {
            $('#addCart').css("background-color", "rgb(225, 130, 45)");
            $("#addCart").text("Ajouter à la liste des mes recettes");
        }
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

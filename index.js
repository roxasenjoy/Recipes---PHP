$(document).ready(function(){

    $(".recipe-link").click(function(e){
        e.preventDefault();  // Empêche l'action par défaut (la navigation)
        
        var id = $(this).data('id');  // Récupère l'ID de l'élément cliqué
        
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
            }
        });
    });


    
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

    function addIngredients(ingredients){
        let ingredientToAdd = '';
        ingredientToAdd += "<p class='title-ingredients'> Ingrédients pour 2 personnes : </p>";
        for (var i = 0; i < ingredients.length; i++) {
            ingredientToAdd += "<p class='ingredients'> - " + ingredients[i].name + "</p>";
        }

        return ingredientToAdd;
    }

    function addName(recette){
        return '<h2  class="recipe-title"> ' + recette.name + '</h2>';
    }

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

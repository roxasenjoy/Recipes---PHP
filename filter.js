$(document).ready(function(){

    resetFilterStorage();

    /**
     * Gestion du temps
     */
    const selectBtn = document.querySelector(".select-btn"),
    items = document.querySelectorAll(".item");

    selectBtn.addEventListener("click", () => {
        selectBtn.classList.toggle("open");
    });

    
    items.forEach(item => {
        item.addEventListener("click", () => {
            item.classList.toggle("checked");

            let checked = document.querySelectorAll(".checked"),
                btnText = document.querySelector(".btn-text")
                itemSelected = [];

                if(checked && checked.length > 0){

                    // Rajouter le nom des éléments cliqués sur le filtre TEMPS
                    checked.forEach(
                        function(node, index) {
                            itemSelected.push(node.innerText);
                        }
                    );

                    /* Ajout dans le Localstorage */
                    var itemNumberOnly = itemSelected.map(elem => elem.replace(" min", ""));
                    localStorage.setItem('time', itemNumberOnly);
                    
                    itemSelected = itemSelected.toString();

                    if(itemSelected.length > 20){
                        itemSelected = itemSelected.substring(0,20);
                        itemSelected = itemSelected + '...';
                    }

                    btnText.innerText = `${itemSelected}`;
                }else{
                    btnText.innerText = "Temps de préparation";
                    resetFilterStorage();
                }
        });
    });


    $(".researchBtn").click(function(e) {
        setupNewRecipesList();
    });


    function resetFilterStorage(){
        localStorage.removeItem('time');
    }


    function setupNewRecipesList(){
        let time = localStorage.getItem('time');
        let research = $(".research").val();
        let canUserRecipesAddedFilter = $("#canUserRecipesAddedFilter").prop('checked');
        let recipesAdded = JSON.parse(localStorage.getItem('recipesAdded'));

    
        $.ajax({
            url: 'filter.php',
            type: 'GET',
            data: { time:time, research:research, recipesAdded: recipesAdded, canUserRecipesAddedFilter:canUserRecipesAddedFilter},
            dataType: 'json', // Add this line, make sure the response is JSON formatted
            success: function(response) {

                // Vider la liste de recette pour la changer par une nouvelle
                const recipesContainer = document.getElementsByClassName('recipes-list')[0];
                recipesContainer.innerHTML = '';

                let listRecipes = '';
                // Loop through each recipe in the response
                response.forEach(function(recipe) {

                    // Create a new recipe DOM element using the recipe data
                    listRecipes += `
                        <a href="#" class="recipe-link" data-id="${recipe.id}" id="${recipe.id}" data-toggle="modal" data-target="#myModal" data-check="false">
                            <div class="container">
                                <img src="${recipe.image}" alt="" loading="lazy">
                                <p class="title">${recipe.name}</p>
                                <div>
                                    ${recipe.time_total ? `
                                        <p class="kcalRecipes detailsContainer">
                                            <i class="fa-solid fa-utensils"></i>
                                            ${recipe.kcal} kcal
                                        </p>
                                        <p class="timeCuisine detailsContainer">
                                            <i class="fa-regular fa-hourglass-half"></i>
                                            ${recipe.time_total} min
                                        </p>
                                    ` : ''}
                                </div>
                            </div>
                        </a>
                    `;
                });

                $(".recipes-list").html(listRecipes);

                addBorderOfRecipesAdded();
                
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function addBorderOfRecipesAdded(){
        addExistingRecipes();
    }

    const $addCart = $('#addCart');
    function addExistingRecipes() {

        $.ajax({
            url: 'recipes_added.php',
            type: 'GET',
            data: { 
                functionToUse: 'getRecipesAddedByUser'
            },
            success: function(response) {
                const listRecipes = response['list_recipes'];
                listRecipes.forEach(recipe => displayDesignIfRecipeSelected(true, recipe));
            },
        }); 
    }

    function displayDesignIfRecipeSelected(isShow, idRecipe) {
        const $recipeElement = $(`#${idRecipe}`);

        if (isShow) {
            $recipeElement.css({
                'outline': 'thick solid rgb(225, 130, 45)',
                'border-radius': '1rem'
            }).attr('data-check', true);
        } else {
            $recipeElement.css({
                'outline': 'initial',
                'border-radius': 'initial'
            }).attr('data-check', false);
        }

        updateButtonDesignAndText($addCart, isShow);
    }

    function updateButtonDesignAndText($element, isShow) {
        if (isShow) {
            $element.css("background-color", "#dc3545").text("Supprimer de ma liste");
        } else {
            $element.css("background-color", "#e1822d").text("Ajouter à ma liste");
        }
    }
    

});

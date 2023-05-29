$(document).ready(function(){


    const selectBtn = document.querySelector(".select-btn"),
    items = document.querySelectorAll(".item");

    selectBtn.addEventListener("click", () => {
        selectBtn.classList.toggle("open");
    });

    let itemSelected = [];
    items.forEach(item => {
        item.addEventListener("click", () => {
            item.classList.toggle("checked");

            let checked = document.querySelectorAll(".checked"),
                btnText = document.querySelector(".btn-text");

                if(checked && checked.length > 0){

                    checked.forEach(e => function(){
                        itemSelected.push(e);
                        console.log(itemSelected);
                    });

                    console.log(itemSelected);

                    btnText.innerText = `${checked.length} temps sélectionnés`;
                }else{
                    btnText.innerText = "Temps";
                }
        });
    });

});

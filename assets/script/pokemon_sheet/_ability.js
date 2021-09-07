
function setBtnCancelAbilities() {
    const btnCancelAbilities = document.getElementById('pokemon-sheet-cancel-button');
    if (btnCancelAbilities) {
        btnCancelAbilities.addEventListener('click', () => {
            const formAbilities = document.getElementById('form-pokemon-sheet-ability');
            formAbilities.classList.add('d-none');
            const href = document.getElementById('pokemon-sheet-current-ability');
            href.classList.remove('d-none');
            const btnEditAbilities = document.getElementById('pokemon-sheet-edit-button');
            btnEditAbilities.classList.remove('d-none');
        });
    }
}

function setBtnAddAbilities() {
    const btnAddAbilities = document.getElementById('pokemon-sheet-add-button');
    if (btnAddAbilities) {
        btnAddAbilities.addEventListener('click', () => {
            const selectFormAbilities = document.getElementsByName('select-pokemon-sheet-ability');
            const pokemonSheetId = document.getElementById('currentPokemonSheetId').textContent;
            selectFormAbilities.forEach((select) => {
                const selectedAbilityId = select.options[select.selectedIndex].value;
                console.log(pokemonSheetId);
                let datas = {
                    'pokemonSheetId': pokemonSheetId,
                    'selectedAbilityId': selectedAbilityId,
                }
                const header = new Headers();
                header.append("Content-Type", "text/html");
                fetch('/pokemonSheet/changeAbility/' + JSON.stringify(datas), header)
                .then((response) => {
                    return response.text();
                }).then(data => {
                    console.log(data);
                }).catch((e) => {
                });
            });
        });
    }

}

function setBtnEditAbilities() {
    const btnEditAbilities = document.getElementById('pokemon-sheet-edit-button');
    if (btnEditAbilities) {
        btnEditAbilities.addEventListener('click', () => {
            const formAbilities = document.getElementById('form-pokemon-sheet-ability');
            formAbilities.classList.remove('d-none');
            const href = document.getElementById('pokemon-sheet-current-ability');
            href.classList.add('d-none');
            btnEditAbilities.classList.add('d-none');
        });
    }
}

setBtnEditAbilities();
setBtnCancelAbilities();
setBtnAddAbilities();


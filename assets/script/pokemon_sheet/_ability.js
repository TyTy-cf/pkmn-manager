
function setBtnCancelAbilities() {
    const btnCancelAbilities = document.getElementById('pokemon-sheet-cancel-button');
    if (btnCancelAbilities) {
        btnCancelAbilities.addEventListener('click', () => {
            const formAbilities = document.getElementById('form-pokemon-sheet-ability');
            formAbilities.classList.add('d-none');
            const href = document.getElementById('pokemon-sheet-current-ability');
            href.classList.remove('d-none');
            enableEditButton();
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
                    const abilityBloc = document.getElementsByClassName('field-form pokemon-sheet-btn');
                    abilityBloc.forEach((element) => {
                        data = JSON.parse(data);
                        element.innerHTML = data[0].html;
                        document.getElementById('form-pokemon-sheet-ability').classList.add('d-none');
                        enableEditButton();
                    });
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

function enableEditButton() {
    const btnEditAbilities = document.getElementById('pokemon-sheet-edit-button');
    btnEditAbilities.classList.remove('d-none');
}

setBtnEditAbilities();
setBtnCancelAbilities();
setBtnAddAbilities();


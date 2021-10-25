import {HTMLData} from '../interface/html_data';

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
            selectFormAbilities.forEach((select: HTMLSelectElement) => {
                const selectedAbilityId = select.options[select.selectedIndex].value;
                let datas = {
                    'pokemonSheetId': pokemonSheetId,
                    'selectedAbilityId': selectedAbilityId,
                }
                fetch('/pokemonSheet/changeAbility/' + JSON.stringify(datas))
                .then((response) => {
                    return response.json() as Promise<HTMLData>;
                })
                .then((data) => {
                    const abilityHtmlElement = document.getElementById('pokemon-sheet-ability-html');
                    abilityHtmlElement.innerHTML = data.html;
                    document.getElementById('form-pokemon-sheet-ability').classList.add('d-none');
                    enableEditButton();
                })
                .catch((e) => {
                });
            });
        });
    }

}

function setBtnEditAbilities() {
    const btnEditAbilities = document.getElementById('pokemon-sheet-edit-button') as HTMLButtonElement;
    if (btnEditAbilities) {
        btnEditAbilities.addEventListener('click', () => {
            const formAbilities: HTMLElement = document.getElementById('form-pokemon-sheet-ability');
            formAbilities.classList.remove('d-none');
            const href: HTMLElement = document.getElementById('pokemon-sheet-current-ability');
            href.classList.add('d-none');
            btnEditAbilities.classList.add('d-none');
        });
    }
}

function enableEditButton() {
    const btnEditAbilities: HTMLElement = document.getElementById('pokemon-sheet-edit-button');
    btnEditAbilities.classList.remove('d-none');
}

setBtnEditAbilities();
setBtnCancelAbilities();
setBtnAddAbilities();


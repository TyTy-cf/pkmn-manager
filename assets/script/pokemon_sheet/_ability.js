
const btnEditAbilities = document.getElementsByClassName('pokemon-sheet-edit-button');

if (btnEditAbilities) {
    btnEditAbilities.forEach((btnEdit) => {
        btnEdit.addEventListener('click', () => {
            const formAbilities = document.getElementById('form-pokemon-sheet-ability');
            formAbilities.classList.remove('d-none');
            const href = document.getElementById('pokemon-sheet-current-ability');
            href.classList.add('d-none');
            btnEdit.classList.add('d-none');
        })
    });
}


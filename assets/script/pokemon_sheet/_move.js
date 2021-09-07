
const btnEditMove = document.getElementById('pokemon-sheet-moves-edit-button');
const btnCancelMove = document.getElementById('id-pokemon-sheet-moves-cancel-button')
const btnAddMove = document.getElementById('id-pokemon-sheet-moves-add-button');

btnEditMove.addEventListener('click', () => {
    document.getElementById('id-form-moves-pokemon-sheet').classList.remove('d-none');
    btnCancelMove.classList.remove('d-none');
    btnAddMove.classList.remove('d-none');
    btnEditMove.classList.add('d-none');
});

btnCancelMove.addEventListener('click', () => {
    document.getElementById('id-form-moves-pokemon-sheet').classList.add('d-none');
    btnCancelMove.classList.add('d-none');
    btnAddMove.classList.add('d-none');
    btnEditMove.classList.remove('d-none');
});

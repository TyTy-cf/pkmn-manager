import {CollapsableManager, CollectionManager} from '@drosalys/form-collection';

const collectionManager = new CollectionManager();

collectionManager.addArrive((event) => {
    if (null !== event.$element) {
        arrive(event.$element);
    }
});

const collapsableManager = new CollapsableManager();

/** Your function use to init js form element */
function arrive($element = document.querySelector('body')) {
    collectionManager.scanForCollection($element);
    collapsableManager.scanForCollapsable($element);
    // Change the css of the new fieldset for form-row
    const formCollapse = document.querySelectorAll('[data-form-collapse]');
    formCollapse.forEach((formCollapse) => {
        formCollapse.className = 'form-row';
    });
    // Hide the button add if there is 4 moves
    const btnAdd = document.getElementById('pokemon_sheet_move_form_moves_btn_add');
    if (formCollapse.length === 4) {
        btnAdd.classList.add('d-none');
    }
    // Add an event on delete button : if there is less than 4 moves, we allow to add more
    const deleteButtons = document.querySelectorAll('[data-prototype-delete]');
    deleteButtons.forEach((deleteBtn) => {
        deleteBtn.addEventListener('click', () => {
            if (formCollapse.length <= 4) {
                btnAdd.classList.remove('d-none');
            }
        });
    });
}

window.addEventListener('load', () => {
    arrive();
});

import {HTMLData} from '../interface/html_data';

setUpHiddenFormBtnEdit();
setUpHiddenFormBtnCancel();
setUpHiddenFormBtnSubmit();

function setUpHiddenFormBtnCancel() {
    const hiddenFormButtonsCancel: NodeListOf<HTMLInputElement> = document.querySelectorAll('[data-cancel-hidden]');
    if (hiddenFormButtonsCancel) {
        hiddenFormButtonsCancel.forEach((btnCancel) => {
            btnCancel.addEventListener('click', () => {
                const linkedFormName = btnCancel.getAttribute('data-cancel-hidden');
                const linkedForm: HTMLElement = document.querySelector('[data-form-hidden="' + linkedFormName + '"]');
                const currentData: HTMLElement = document.querySelector('[data-original-hidden="' + linkedFormName + '"]');
                linkedForm.classList.add('d-none');
                currentData.classList.remove('d-none');
                enableEditButton(linkedFormName);
            });
        });
    }
}

function setUpHiddenFormBtnSubmit() {
    const hiddenFormButtonsSubmit: NodeListOf<HTMLInputElement> = document.querySelectorAll('[data-submit-hidden]');
    if (hiddenFormButtonsSubmit) {
        hiddenFormButtonsSubmit.forEach((btnSubmit) => {
            btnSubmit.addEventListener('click', () => {
                const linkedFormName = btnSubmit.getAttribute('data-submit-hidden');
                const hiddenFormInput: HTMLElement = document.querySelector('[data-input-hidden="' + linkedFormName + '"]');
                let inputValue = '';
                if (hiddenFormInput instanceof HTMLSelectElement) {
                    inputValue = hiddenFormInput.options[hiddenFormInput.selectedIndex].value;
                } else if (hiddenFormInput instanceof HTMLInputElement) {
                    inputValue = hiddenFormInput.value;
                }
                let datas = {
                    'pokemonSheetId': document.querySelector('[data-entity-id="' + linkedFormName + '"]').textContent,
                    'inputDataId': inputValue,
                }
                fetch(btnSubmit.getAttribute('data-action-hidden') + JSON.stringify(datas))
                .then((response) => {
                    return response.json() as Promise<HTMLData>;
                })
                .then((data) => {
                    const originalHtml: HTMLElement = document.querySelector('[data-original-hidden="' + linkedFormName + '"]')
                    originalHtml.innerHTML = data.html;
                    originalHtml.classList.remove('d-none');
                    document.querySelector('[data-form-hidden="' + linkedFormName + '"]').classList.add('d-none');
                    enableEditButton(linkedFormName);
                })
                .catch((e) => {
                });
            });
        });
    }
}

function setUpHiddenFormBtnEdit() {
    const hiddenFormButtonsEdit: NodeListOf<HTMLInputElement> = document.querySelectorAll('[data-edit-hidden]');
    if (hiddenFormButtonsEdit) {
        hiddenFormButtonsEdit.forEach((btnEdit) => {
            btnEdit.addEventListener('click', () => {
                const linkedFormName = btnEdit.getAttribute('data-edit-hidden');
                const linkedForm: HTMLElement = document.querySelector('[data-form-hidden="' + linkedFormName + '"]');
                const currentData: HTMLElement = document.querySelector('[data-original-hidden="' + linkedFormName + '"]');
                linkedForm.classList.remove('d-none');
                currentData.classList.add('d-none');
                btnEdit.classList.add('d-none');
            });
        });
    }
}

function enableEditButton(linkedFormName: string) {
    const hiddenFormButtonsEdit: HTMLElement = document.querySelector('[data-edit-hidden="' + linkedFormName + '"]');
    hiddenFormButtonsEdit.classList.remove('d-none');
}


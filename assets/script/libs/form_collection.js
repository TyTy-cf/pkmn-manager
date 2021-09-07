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
}

window.addEventListener('load', () => {
    arrive();
});

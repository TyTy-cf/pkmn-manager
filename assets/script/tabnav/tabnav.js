
function detailedMoves(evt, version) {
    // Declare all variables
    let i, tabcontent, tablinks;

    // Get all elements with class="selector-tabContent" and hide them
    tabcontent = document.getElementsByClassName('selector-tabContent');
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = 'none';
    }

    // Get all elements with class="selector-tabNavigationButton" and remove the class "active"
    tablinks = document.getElementsByClassName('selector-tabNavigationButton');
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(' active', '');
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById('tab-'+version).style.display = 'block';
    evt.currentTarget.className += ' active';
}

/**
 * TabNav works on tablinks classes
 */
window.addEventListener('load', () => {
    let tablinks = document.getElementsByClassName('selector-tabNavigationButton');
    for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].addEventListener('click', (evt) => {
            detailedMoves(evt, tablinks[i].id);
        });
    }
});
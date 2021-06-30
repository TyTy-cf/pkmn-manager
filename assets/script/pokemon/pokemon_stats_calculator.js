
const buttonStatsSubmit = document.getElementById('calculate_stats_form_submit');
buttonStatsSubmit.addEventListener('click', (e) => {
    e.preventDefault();
    const ivHp = document.getElementById('calculate_stats_form_ivPv').value;
    const ivAtk = document.getElementById('calculate_stats_form_ivAtk').value;
    const ivDef = document.getElementById('calculate_stats_form_ivDef').value;
    const ivSpa = document.getElementById('calculate_stats_form_ivSpa').value;
    const ivSpd = document.getElementById('calculate_stats_form_ivSpd').value;
    const ivSpe = document.getElementById('calculate_stats_form_ivSpe').value;
    const evHp = document.getElementById('calculate_stats_form_evPv').value;
    const evAtk = document.getElementById('calculate_stats_form_evAtk').value;
    const evDef = document.getElementById('calculate_stats_form_evDef').value;
    const evSpa = document.getElementById('calculate_stats_form_evSpa').value;
    const evSpd = document.getElementById('calculate_stats_form_evSpd').value;
    const evSpe = document.getElementById('calculate_stats_form_evSpe').value;
    const level = document.getElementById('calculate_stats_form_level').value;
    const nature = document.getElementById('calculate_stats_form_nature').value
    const idPokemon = document.getElementById('pokemonProfileId').innerText;

    let datas = {'toCalculate' : 'stats', ivHp, ivAtk, ivDef, ivSpa, ivSpd, ivSpe, evHp, evAtk, evDef, evSpa, evSpd, evSpe, level, nature, idPokemon}
    const header = new Headers();
    header.append("Content-Type", "text/html");
    fetch('/pokemons/calculate/' + JSON.stringify(datas), header)
        .then((response) => {
            return response.text();
        }).then(data => {
        const results = document.getElementById('stats-results');
        data = JSON.parse(data);
        results.innerHTML = data.html;
        results.className = results.className.replace('collapse', '');
        setHideResultPanel();
    }).catch((e) => {
    })
    ;
});

function setHideResultPanel() {
    const arrayHideCalculatorResult = document.getElementsByClassName('close-calculator');
    arrayHideCalculatorResult.forEach((closeButton) => {
        closeButton.addEventListener('click', () => {
            let divToClose = closeButton.closest('div .result-calculator');
            divToClose.className += ' collapse';
        });
    });
}


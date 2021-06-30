
const buttonIvSubmit = document.getElementById('calculate_iv_form_submit');
buttonIvSubmit.addEventListener('click', (e) => {
   e.preventDefault();
   const statsHp = document.getElementById('calculate_iv_form_statsPv').value;
   const statsAtk = document.getElementById('calculate_iv_form_statsAtk').value;
   const statsDef = document.getElementById('calculate_iv_form_statsDef').value;
   const statsSpa = document.getElementById('calculate_iv_form_statsSpa').value;
   const statsSpd = document.getElementById('calculate_iv_form_statsSpd').value;
   const statsSpe = document.getElementById('calculate_iv_form_statsSpe').value;
   const evHp = document.getElementById('calculate_iv_form_evPv').value;
   const evAtk = document.getElementById('calculate_iv_form_evAtk').value;
   const evDef = document.getElementById('calculate_iv_form_evDef').value;
   const evSpa = document.getElementById('calculate_iv_form_evSpa').value;
   const evSpd = document.getElementById('calculate_iv_form_evSpd').value;
   const evSpe = document.getElementById('calculate_iv_form_evSpe').value;
   const level = document.getElementById('calculate_iv_form_level').value;
   const nature = document.getElementById('calculate_iv_form_nature').value
   const idPokemon = document.getElementById('pokemonProfileId').innerText;

   let datas = {'toCalculate' : 'iv', statsHp, statsAtk, statsDef, statsSpa, statsSpd, statsSpe, evHp, evAtk, evDef, evSpa, evSpd, evSpe, level, nature, idPokemon}
   const header = new Headers();
   header.append("Content-Type", "text/html");
   fetch('/pokemons/calculate/' + JSON.stringify(datas), header)
       .then((response) => {
            return response.text();
       }).then(data => {
            const results = document.getElementById('iv-results');
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


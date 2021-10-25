import {HTMLData} from "../interface/html_data";

function ajaxIvCalculation(): void {
    const buttonIvSubmit = document.getElementById('calculate_iv_form_submit') as HTMLButtonElement;
    if (buttonIvSubmit) {
        const statsHp = (<HTMLInputElement>document.getElementById('calculate_iv_form_statsPv')).value;
        const statsAtk = (<HTMLInputElement>document.getElementById('calculate_iv_form_statsAtk')).value;
        const statsDef = (<HTMLInputElement>document.getElementById('calculate_iv_form_statsDef')).value;
        const statsSpa = (<HTMLInputElement>document.getElementById('calculate_iv_form_statsSpa')).value;
        const statsSpd = (<HTMLInputElement>document.getElementById('calculate_iv_form_statsSpd')).value;
        const statsSpe = (<HTMLInputElement>document.getElementById('calculate_iv_form_statsSpe')).value;
        sendAjaxDatasAndAddHtml(buttonIvSubmit, statsHp, statsAtk, statsDef, statsSpa, statsSpd, statsSpe, 'iv');
    }
}

function ajaxStatsCalculation(): void {
    const buttonStatsSubmit = document.getElementById('calculate_stats_form_submit') as HTMLButtonElement;
    if (buttonStatsSubmit) {
        const ivHp = (<HTMLInputElement>document.getElementById('calculate_stats_form_ivPv')).value;
        const ivAtk = (<HTMLInputElement>document.getElementById('calculate_stats_form_ivAtk')).value;
        const ivDef = (<HTMLInputElement>document.getElementById('calculate_stats_form_ivDef')).value;
        const ivSpa = (<HTMLInputElement>document.getElementById('calculate_stats_form_ivSpa')).value;
        const ivSpd = (<HTMLInputElement>document.getElementById('calculate_stats_form_ivSpd')).value;
        const ivSpe = (<HTMLInputElement>document.getElementById('calculate_stats_form_ivSpe')).value;
        sendAjaxDatasAndAddHtml(buttonStatsSubmit, ivHp, ivAtk, ivDef, ivSpa, ivSpd, ivSpe, 'stats');
    }
}

function sendAjaxDatasAndAddHtml(
    buttonSubmit: HTMLButtonElement,
    hp: string, atk: string, def: string, spa: string, spd: string, spe: string, toCalculate: string
): void {
    if (buttonSubmit !== null) {
        buttonSubmit.addEventListener('click', (e) => {
            e.preventDefault();
            const evHp = (<HTMLInputElement>document.getElementById('calculate_stats_form_evPv')).value;
            const evAtk = (<HTMLInputElement>document.getElementById('calculate_stats_form_evAtk')).value;
            const evDef = (<HTMLInputElement>document.getElementById('calculate_stats_form_evDef')).value;
            const evSpa = (<HTMLInputElement>document.getElementById('calculate_stats_form_evSpa')).value;
            const evSpd = (<HTMLInputElement>document.getElementById('calculate_stats_form_evSpd')).value;
            const evSpe = (<HTMLInputElement>document.getElementById('calculate_stats_form_evSpe')).value;
            const level = (<HTMLInputElement>document.getElementById('calculate_stats_form_level')).value;
            const nature = (<HTMLInputElement>document.getElementById('calculate_stats_form_nature')).value
            const idPokemon = document.getElementById('pokemonProfileId').innerText;

            let datas = {
                toCalculate,
                hp, atk, def, spa, spd, spe,
                evHp, evAtk, evDef, evSpa, evSpd, evSpe,
                level, nature, idPokemon
            }
            fetch('/pokemons/calculate/' + JSON.stringify(datas))
            .then((response: Response) => {
                return response.json() as Promise<HTMLData>;
            })
            .then(data => {
                let results = document.getElementById('iv-results');
                if (data.calculation === 'stats') {
                    results = document.getElementById('stats-results');
                }
                results.innerHTML = data.html;
                results.className = results.className.replace('collapse', '');
                setHideResultPanel();
            })
            .catch((e) => {
            });
        });
    }
}

function setHideResultPanel() {
    const arrayHideCalculatorResult = Array.from(document.getElementsByClassName('close-calculator'));
    arrayHideCalculatorResult.forEach((closeButton) => {
        closeButton.addEventListener('click', () => {
            let divToClose = closeButton.closest('div .result-calculator');
            divToClose.className += ' collapse';
        });
    });
}

ajaxIvCalculation();
ajaxStatsCalculation();


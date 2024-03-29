const autocomplete = require('autocompleter');

let arrayPokemonNames = [];
const input = document.getElementById('search_pokemon_name_pokemon') as HTMLInputElement;
const formSearch = document.forms['search_pokemon'];

if (input) {
    autocomplete({
        minLength: 1,
        input: input,
        fetch: function(text, update) {
            text = text.toLowerCase();
            fetch('/pokemons/searchPokemonByName/' + text)
                .then((response) => {
                    return response.json();
                })
                .then((pokemons) => {
                    arrayPokemonNames = pokemons;
                })
                .catch((e) => {
                })
            ;
            const suggestions = arrayPokemonNames.filter(n => n.name.toLowerCase().startsWith(text));
            update(suggestions);
        },
        onSelect: function(item) {
            input.value = item.name;
        },
        render: function(item, currentValue) {
            const itemElement = document.createElement("div");
            itemElement.classList.add('autocomplete-item');
            itemElement.textContent = item.name;
            itemElement.addEventListener('click', ()=> {
                const inputForm = document.getElementById('search_pokemon_name_pokemon') as HTMLInputElement;
                inputForm.value = item.name;
                formSearch.submit();
            });
            return itemElement;
        }
    });
}



# pkmn-manager

## Start the project

When getting the project :

```bash
composer install
```

The application use MariaDB, once you setup your local server, just run :

```bash
php bin/console doctrine:migrations:migrate
```

Now you need to run the fixtures (just few nomenclature) :

```
php bin/console doctrine:fixtures:load --append
```

Actual supported language : french (fr) and english (en)

And then come all the command to fetch all pokemons and their informations :


(Add the required language each time : fr, en, ...)

```bash
symfony console app:region:all
symfony console app:generation:all
symfony console app:version-group:all
symfony console app:version:all
symfony console app:ability:all
symfony console app:nature:all
symfony console app:damage-class:all
symfony console app:egg-group:all
symfony console app:type:all
symfony console app:damage-relation:all
symfony console app:move:all
symfony console app:pokemon:all
symfony console app:move-learn-method:all
symfony console app:pokemon-form:all
symfony console app:pokemon-species:all
symfony console app:move-machine:all
symfony console app:evolution-trigger:all
symfony console app:pokedex:all
symfony console app:evolution:all
symfony console app:pokemon-move-learn:all 'offset' 'limit' 'language' (I recommand launching this one 15 by 15 until gen 3 is reached, then increase to 0 30)
```

## Style linter

Get errors from scss files:

```
yarn style:lint
```

Fix the fixable errors:

```
yarn style:fix
```

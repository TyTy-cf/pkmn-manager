# pkmn-manager

When getting the project :

``
composer install
``

``
yarn install
``

The application use MariaDB, once you setup your local server, just run :

``
php bin/console doctrine:migrations:migrate
``

Now you need to run the fixtures (just few nomenclature) :

``
php bin/console doctrine:fixtures:load --append
``

Actual supported language : french and english

And then come all the command to fetch all pokemons and their informations :

``
symfony console app:ability:all 'language'
``
<br>
``
symfony console app:nature:all 'language'
``
<br>
``
symfony console app:damage-class:all 'language'
``
<br>
``
symfony console app:egg-group:all 'language'
``
<br>
``
symfony console app:item:all 'language'
``
<br>
``
symfony console app:move:all 'language'
``
<br>
``
symfony console app:move-learn-method:all 'language'
``
<br>
``
symfony console app:type:all 'language'
``
<br>
``
symfony console app:damage-relation:all 'language'
``
<br>
``
symfony console app:version-group:all 'language'
``
<br>
``
symfony console app:version:all 'language'
``
<br>
``
symfony console app:pokemon:all 'language'
``
<br>
``
symfony console app:pokemon-form:all 'language'
``
<br>
``
symfony console app:pokemon-species:all 'language'
``
<br>
``
symfony console app:sprites:all 'language'
``
<br>
``
symfony console app:pokedex:all 'language'
``
<br>
``
symfony console app:move-machine:all 'language'
``
<br>
``
symfony console app:move-learn-method:all 'offset' 'limit' 'language' (I recommand launching this one 15 by 15 until gen 3 is reached, then increase to 0 30)
``

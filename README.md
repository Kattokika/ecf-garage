# Projet ECF STUDI: Garage V. Parrot

Ce projet repose sur le framework PHP [Symfony](https://symfony.com).

Afin d'exécuter en local ce projet, plusieurs dépendances sont nécessaires : `php`, `composer` et `docker` pour les services comme la base de donnée PostgreSQL, gérée automatiquement par Symfony.

## Symfony CLI
La première, la plus simple, va utiliser `Symfony CLI` pour nous faciliter la tâche. Commencer par installer `php` et `composer` si ceux-ci ne sont pas présents.
Pour installer PHP : https://www.php.net/manual/fr/install.php
Pour installer Composer : https://getcomposer.org/download/
Ensuite, nous pourrons installer `symfony CLI` : https://symfony.com/download
Et pour Docker : https://docs.docker.com/get-docker/

Il suffira alors de lancer `composer install`, puis `docker compose up` afin de lancer la base de données.

Ensuite, il faudra valider le schema de la base de données avec la commande suivante :
```bash
$ symfony console doctrine:schema:update --force
```

A noter que ceci est une commande à exécuter dans un environnement de développement seulement, car elle peut être très destructrice.
Celle-ci compare votre base de données à son schema interne, et modifiera la base de données pour être conforme.
Le mieux étant de profiter des migrations, qui stockent des fichiers avec ces commandes SQL qui vous permettent de vérifier ce qu'il se passe avant d'executer.
Plus d'informations sur le lien suivant: https://symfony.com/doc/3.3/doctrine.html#creating-the-database-tables-schema

Nous pourrons ensuite lancer le serveur PHP qui nous donnera accès à notre application.

symfony server:start


### Créer l'administrateur
Nous pourrons ensuite créer l'administrateur nous permettant d'avoir accès à l'espace professionnel.
Pour commander, nous allons créer le hash du mot de passe que nous souhaitons pour notre administrateur.
Dans ce test, nous allons utiliser le mot de passe suivant : `bag-microwave-plank-32`.

```bash
$ symfony console security:hash-password
--------------- -----------------------------------------------------------------
Key Value
--------------- -----------------------------------------------------------------
Hasher used Symfony\Component\PasswordHasher\Hasher\MigratingPasswordHasher
Password hash $2y$13$i3som1ymIgHKL87qP7BN4.3dQuNvacMw99sRijs/QeVTCsZr06tmK
--------------- -----------------------------------------------------------------
```

Attention, il faut escape les `$` et `"` lorsque l'on passe directement depuis le terminal.
```bash
$ symfony run psql -c "INSERT INTO "user" (id, email, roles, password, nom, prenom, poste) VALUES (nextval('user_id_seq'), 'vincent@garageparrot.fr', '[\"ROLE_ADMIN\"]', '\$2y\$13\$XKW5tG4NoIXSLzgCy6B1w.EmXecq8InSoSlXDjJt2DA74Ugt5HHYi', 'PARROT', 'Vincent', 'Directeur')"
```
Ou directement depuis `psql`:
```bash
$ psql -U app
$ > INSERT INTO "user" (id, email, roles, password, nom, prenom, poste) VALUES (nextval('user_id_seq'), 'vincent@garageparrot.fr', '["ROLE_ADMIN"]', '$2y$13$i3som1ymIgHKL87qP7BN4.3dQuNvacMw99sRijs/QeVTCsZr06tmK', 'PARROT', 'Vincent', 'Directeur');
```

### Insérer des fausses données de test

Certains fichiers sont disponibles afin d'insérer des données de test pour faciliter le test de l'application :
```bash
$ psql -U app -d app -a -f ./tests/sql/horaires.sql
$ psql -U app -d app -a -f ./tests/sql/vehicule_carburant.sql
```

.\node_modules\.bin\encore dev --watch

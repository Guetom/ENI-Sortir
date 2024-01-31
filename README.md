[![Tests Unitaire](https://github.com/Guetom/ENI-Sortir/actions/workflows/UnitTests.yml/badge.svg)](https://github.com/Guetom/ENI-Sortir/actions/workflows/UnitTests.yml)
# ENI Sortir

Projet symfony dans le cadre de la formation CDA de l'ENI.

## Auteurs

- [Thomas GUEDON](https://github.com/Guetom)
- [Romain CORNUAULT](https://github.com/TheMisterRedFox)
- [Julien BROUILLARD](https://github.com/AMIRALADAMS)

---

### Description

ENI Sortir est une application web permettant de gérer des sorties entre amis. Elle permet de créer des sorties, de
s'inscrire à des sorties, de gérer des lieux, de gérer des villes, de gérer des états de sorties, de gérer des campus,
de gérer des utilisateurs, de gérer des groupes, de gérer des sites, de gérer des organisateurs, de gérer des
participants, de gérer des inscriptions, de gérer des sorties, de gérer des commentaires, de gérer des images, ...

## Installation

### Prérequis

- PHP 8.1
- Composer
- Symfony CLI
- MySQL
- NodeJS
- NPM
- Git

### Procédure

1. Cloner le projet
2. Installer les dépendances Composer avec `composer install`
3. Installer les dépendances NP% avec `npm install`
4. Créer un fichier `.env.local` à la racine du projet et y ajouter les informations de connexion à la base de données
5. Créer la base de données avec `symfony console doctrine:database:create`
6. Créer les tables avec `symfony console doctrine:migrations:migrate`
7. Charger les données de test avec `symfony console doctrine:fixtures:load`
8. Compiler les assets avec `npm run build` ou `npm run watch` pour compiler automatiquement à chaque modification
9. Lancer le serveur avec `symfony serve` ou `symfony serve -d` pour le lancer en arrière-plan
10. Se rendre sur [localhost:8000](https://localhost:8000) pour accéder au site

## Tests

Commande pour lancer les tests :
````shell
php bin/phpunit
````

Commande pour lancer les tests avec plus de détails :
````shell
php bin/phpunit --testdox
````

## Utilisation

### Importation de compte utilisateur

Pour importer des comptes utilisateurs, il faut créer un fichier CSV avec les colonnes suivantes :

> [!NOTE]
> Le séparateur de colonne doit être le point-virgule (;)

- Prénom
- Nom
- Pseudo
- Mail
- Téléphone (facultatif)
- Site (Campus ENI Nantes,Campus ENI Rennes, ...)

> [!WARNING]
> ATTENTION : La première ligne du fichier doit être le nom des colonnes

> [!IMPORTANT]
> Tout ligne ne respectant pas le format ci-dessus sera ignorée

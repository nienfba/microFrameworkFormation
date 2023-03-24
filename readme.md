# Premier pas vers un framework en POO

Une petite démo de site présentant des produits, développé en POO :

- utilisation de quelques principes SOLID
- utilisation d'un système de routage automatique (non indexé)
- utilisation des namespaces et de l'autoload
- utilisation de dépendances avec composer (twig et faker)
- utilisation du pattern MVC
- utilisation d'entités pour utiliser la data dans l'application

# Installation

⚠️ Exemple de code pour l'utilisation en formation seulement !

- Téléchargez le dossier ou clonez le !

- Installation des dépendances (Twig et Faker) et gestion de l'autoload :

```bash

composer install

```

- Installation de la base de donnée : 
> sur MySql ou MariaDb créez une base de données et importez le schéma `sql/microFramework.sql`
  
- Créer le fichier de config `config/config.php` à partir de `config/config.exemple.php`

- Modifier dans le fichier de config : 
  - le DSN de connexion à la base de données. COnstante `DB_DSN`
  - l'utilisateur et le mot de passe de la base de données. Constantes `DB_USERNAME` et `DB_PASSWORD`
  - la valeur de la constante URL, par exemple : `http://localhost/demoMicroFramework/`


# Test

- Créer des nouveaux produits dans la base en appellant la route : `/admin/addProductFake/`

- Aller à l'URL de votre projet pour voir la liste des produits : `http://localhost/demoMicroFramework/`

# Version 

## V0.1

- Ajout des insert/update génériques dans la classe `app/Classes/Model.php`
- Gestion de la transformation en tableau des propriétés des entités `app/Classes/Entity.php` pour l'ajout à la requête du model
- Modification du framework CSS. Ajout de Bootstrap.
- Ajout d'une page de détail pour les produits
- Ajout du fichier `config.exemple.php`
- Ajout de la méthode `addProductFake` dans le controller `Admin` pour démo d'ajout de produit
- Ajout de la méthode `truncate` dans les models pour vider une table ⚠️ pas de confirmation !
- Ajout de la vue `produit.html.twig` pour générer une page de détail produit
- La constante de configuration `URL` est maintenant passée aux vues twig automatiquement pour la génération des liens des assets et d'accueil
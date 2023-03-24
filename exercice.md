# Projet Cart

Nous allons créer un petit projet pour gérer des produits et l'ajout de ses produits dans un panier.

Nous allons respecter le pattern MVC et les concepts SOLID.


## Etape 1 : arborescence du projet

Reportez vous à la structure de dossier fournie pour l'application.

## Etape 2 : modéliser notre première entité

On va représenter nos données à l'aide d'entités dans notre application.

Une entité et une données représenté sous la forme d'un objet.

Elle comporte des propriétés qui correspondent aux valeurs de la données.

Elle a la responsabilité unique de représenter une données. Aucune autre méthode ne doit lui être rattachée hormis les getters et setters d'accès aux propriétés.

Nous allons donc créer une entité `Product`

Puis nous allons créer un fichier de test pour tester cette entité. Créez et hydrater l'objet.

## Etape 3 : création d'une classe Database

Nous voulons nous connecter à la base de données mais être sûr que nous utilisons toujours la même instance PDO de connexion.

> Comment faire pour que à chaque instanciation de Database `new Database()` nous utilisions toujours la même instance de PDO ?

Nous voulons aussi simplifier l'accès aux trois grandes requêtes les plus utlisées, notamment en simplifiant notre code pour l'utilisation des requêtes préparées.

- select all => récupérer plusieurs éléments (fetchAll)
- select one => récupérer un élément (fetch)
- update/insert/delete => executer une requête sans retour de donnée.

> Comment allons nous faire pour avoir une seule instance de connexion ?

> Quelle va être le diagramme de notre classe Database ?

> Où allons nous mettre les données de connexion à notre base (mdp, user...) ?

**Exemple d'utilisation**

```php
    $database = new Database();
    $database->select('Select * From product where name like %:text%',['text'=>'toto']);
    $database->selectOne('Select * From product where id=:id',['id'=>10]);
```

## Etape 4 : création d'une modèle pour nos produits

Cette classe va nous permettre de gérer notre CRUD pour nos produits.

On pourra par exemple appeler une méthode `find(10)` qui va nous transmettre un objet de type `Entity\Product` qui sera hydraté avec les données du produit N°10 dans la data.

Ou utiliser une méthode `findAll()` qui va nous transmettre une collection de type `EntityCollection` qui contiendra des objets de type `Entity\Product`

> De quoi va avoir besoin cette classe pour faire ces actions ?
 
> Une fois les données récupérée que devons nous faire ?

> Et comment le faire ?

> Quelles seront les autres méthodes de cette classe ?

> A terme nous aurons d'autres modèles... que peut-on faire pour faciliter la création d'autres modèles ?

## Etape 5 : gestion des dépendances avec Composer

Nous allons utiliser **Faker** pour créer des données Fake et **Twig** pour générer nos vues.
Nous allons donc installer ses dépendances avec **Composer**.

Nous utiliserons aussi **Composer** pour gérer l'autoload de nos classes.

Nous allons donc donner un NameSpace à toutes nos classe dans le dossier App/

A votre convenance inventez un vendor ... pour moi ce sera `Fab/`

Donc ma classe entité Product aura comme namaspace : `Fab/Entity`

Ma classe Database, dans le sous dossiers classes/ aura : `Fab/Classes`

IL nous reste à mettre dans le composer.json le paramètre pour l'autoload : 

```json
{
    #...
    "autoload": {
        "psr-4": {
            "Fab\\": "app/"
        }
    },
    "require": {
        #...
    },
    "require-dev": {
       #....
    }
    #...
}
```

Et de recharcher les autoload de composer en ligne de commande : 

`composer dump-autoload`

## Etape 6 : création de notre premier controller

## Etape 7 : créer des données Fake dans notre data

## Etape 8 : afficher notre page d'accueil avec Twig

## Etape 9 : gérer l'ajout à un panier

La gestion du panier doit pouvoir se faire en session ou dans la base de donnée.
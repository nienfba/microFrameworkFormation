<?php

namespace Fab\Controller;

use Faker\Factory;
use Fab\Entity\Product;
use Fab\Classes\Controller;
use Fab\Model\ProductModel;

class AdminController extends Controller {

    public function index() {
        return $this->render('admin/dashboard.html.twig');
    }


    /**
     * Création de donnée Fake
     */
    public function addProductFake() {

        // On vide la table Product
        $productModel = new ProductModel();
        $productModel->truncate();

        // Utilisation de faker 
        $faker = Factory::create();

        // Création de 50 produit
        for($i=1;$i<=50;$i++) {

            $product = new Product();
            $product->setName( $faker->word())
            ->setPrice($faker->randomFloat(2))
            ->setQuantity($faker->randomNumber(4))
            ->setCreatedAt($faker->dateTime())
            ->setDescription($faker->text())
            ->setPicture('https://picsum.photos/1024/1024?random='.$i)
            ->setTva(20);

            $productModel->save($product);
        }
        
    }

}
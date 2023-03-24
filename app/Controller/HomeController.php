<?php

namespace Fab\Controller;

use Fab\Classes\Controller;
use Fab\Model\ProductModel;

class HomeController extends Controller {


    public function index() {
        $productModel = new ProductModel();
        $products = $productModel->findAll();

        return $this->render('front/home.html.twig', ['products'=> $products]);

    }

    public function product(int $id)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);
        
        return $this->render('front/product.html.twig', ['product' => $product]);
    }
}
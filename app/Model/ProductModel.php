<?php

namespace Fab\Model;

use Fab\Classes\Model;


class ProductModel extends Model {
    

    public function __construct()
    {
        parent::__construct();
        $this->table = 'product';
    }

}
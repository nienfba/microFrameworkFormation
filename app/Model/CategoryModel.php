<?php



namespace Fab\Model;

use Fab\Classes\Model;


class CategoryModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'category';
    }
}
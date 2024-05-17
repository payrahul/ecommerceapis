<?php

namespace App\Service;
use App\Models\ProductCategories;

class CreateService implements CreateServiceInterface{

    public function doCreateServiceThing(){

        // echo "test";
        $produtWithCategory = ProductCategories::all();

        echo $produtWithCategory;
        // return ['check'=>$produtWithCategory];
    }
}
?>
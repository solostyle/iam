<?php

class CategoriesController extends Controller {

    function index($queryArray) {
        $this->set('category', $queryArray[0]); // assuming only one item in array
    }
}

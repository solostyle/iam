<?php

class TagsController extends Controller {

    function index($queryArray) {
        $queryString = $queryArray[0]; // assuming only one item in array
        if (strpos($queryString, '|')) {
            $this->set('method', 'or');
            $this->set('tags_arr', explode('|', $queryString));
        } elseif (strpos($queryString, '&')) {
            $this->set('method', 'and');
            $this->set('tags_arr', explode('&', $queryString));
        } else {
            $this->set('tags_arr',array($queryString));
            $this->set('method','');
        }
    }
}

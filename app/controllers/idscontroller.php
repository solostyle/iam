<?php

class IdsController extends Controller {

  function index($queryString) {
    //$this->doNotRenderHeader = true;
    $this->set('blog_id',$queryString);
  }
}

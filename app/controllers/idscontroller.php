<?php

class IdsController extends Controller {

  function index($y, $m=null, $title=null) {
    // reconstructing the querystring like this
    // means the trailing solidus is now missing for
    // urls like 2006/ and 2006/03/
    $this->set('blog_id', implode("/", array($y, $m, $title)));
  }
}

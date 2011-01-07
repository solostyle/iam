<?php

class ArchmenuController extends Controller {

  function index($isAjaxR=true) {
    if ($isAjaxR) $this->doNotRenderHeader = true;
    //$this->set('archmenu',$this->Archlink->custom());
    // thinking about putting all of create_archive_nav_array() in here
  }

}
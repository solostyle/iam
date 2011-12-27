<?php

class ArchmenuController extends Controller {

	// currently unused
	function index($isAjaxR=true) {
		if ($isAjaxR) $this->doNotRenderHeader = true;
		//$this->set('archmenu',$this->Archlink->custom());
		// thinking about putting all of create_archive_nav_array() in here
	}
  
	// Use this when you need to update the javascript object (!)
	function menu($isAjaxR=true) {
		if ($isAjaxR) $this->doNotRenderHeader = true;
	}

}
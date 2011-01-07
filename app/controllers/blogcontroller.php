<?php

class BlogController extends Controller {

  function view($id = null,$name = null) {
    $this->Entry->id = $id;
    $entry = $this->Entry->search();
    $this->set('entry',$entry);
  }

  function index($isAjaxR=true) {
    if ($isAjaxR) $this->doNotRenderHeader = true;
    $this->Entry->regexp('id','^2010');
    $this->Entry->orderBy('time','DESC');
    $this->set('blog',$this->Entry->search());
  }

  function all($includeForm) {
    $this->doNotRenderHeader = true; /* i want this to be an ajax request*/
    $this->Entry->orderBy('time','DESC');
    $this->set('blog',$this->Entry->search());
    $this->set('includeForm',$includeForm);  // looks like this has to be defined
  }

/* make sure ID is big enough */
  function add() {
    $this->doNotRenderHeader = true; /* i want this to be an ajax request*/
    $this->Entry->entry = $_POST['entry'];
    $this->Entry->title = $_POST['title'];
    $this->Entry->save();
  }

  function delete() {
    $this->doNotRenderHeader = true;
    $this->Entry->id = $_POST['id'];
    $this->Entry->delete();
  }
}
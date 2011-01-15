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

    $blog_id = create_id($_POST['title'], $_POST['year'], $_POST['month'], $_POST['date']);
    
    $addEntry_values = array($blog_id, mysql_real_escape_string($_POST['time']), mysql_real_escape_string($_POST['title']), mysql_real_escape_string($_POST['entry']));

    $addEntry_fields = array('id','time','title','entry');

    select_db();
    insert_record('blog', $addEntry_fields, $addEntry_values);
    assign_category($blog_id, mysql_real_escape_string($_POST['category']));
    mysql_close();
}

  function delete() {
    $this->doNotRenderHeader = true;
    $this->Entry->id = $_POST['id'];
    $this->Entry->delete();
  }
}
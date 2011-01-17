<?php

class BlogController extends Controller {

  function id($queryArray) {
    $this->doNotRenderHeader = true;
    $this->Entry->regexp('id',implode("/", $queryArray));
    $this->Entry->orderBy('time','DESC');
    $this->set('blog', $this->Entry->search());
  }

  function index() {
    $this->doNotRenderHeader = true;
    $this->Entry->setLimit(3);
    $this->Entry->orderBy('time','DESC');
    $this->set('blog', $this->Entry->search());
    //$this->set('blog', $this->Entry->custom("SELECT * FROM `blog` ORDER BY `time` DESC LIMIT 3"));
  }

    // not using yet
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
    
    $values = array($blog_id, mysql_real_escape_string($_POST['time']), mysql_real_escape_string($_POST['title']), mysql_real_escape_string($_POST['entry']));

    $fields = array('id','time','title','entry');

    select_db();
    insert_record('blog', $fields, $values);
    assign_category($blog_id, mysql_real_escape_string($_POST['category']));
    mysql_close();
}

  function delete() {
    // first copy the row to the deleted_blog table!
    $this->Entry->id = $_POST['id'];
    $entry = $this->Entry->search();

    $values = array($entry['Entry']['id'], $entry['Entry']['time'], $entry['Entry']['title'], $entry['Entry']['entry']);
    $fields = array('id','time','title','entry');

    select_db();
    insert_record('deleted_blog', $fields, $values);
//    mysql_close(); // the next lines to delete() will fail if mysql_close() runs
    
    // now delete from main blog table
    $this->doNotRenderHeader = true;
    $this->Entry->id = $_POST['id'];
    $this->Entry->delete();
  }
}
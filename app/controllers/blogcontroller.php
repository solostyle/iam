<?php

class BlogController extends Controller {

// first item in $queryArray must be 0 or 1, for whether to render header
// set to 0 in routing.php
// not set to 1 anywhere as of 1 dec 11

    function id($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
        $this->Entry->regexp('id',implode("/", $queryArray));
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }

    function tag($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
        $ids = rtrv_ids_by_tag($queryArray);
        $this->Entry->in('id',implode("','",$ids));
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }
    
    function category($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
        $ids = rtrv_ids_by_category(str_replace("_", " ",$queryArray[0]));
        $this->Entry->in('id',implode("','",$ids));
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }
    
    function index($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
		$this->Entry->setPage(1);
        $this->Entry->setLimit(1);
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }

    function add() {
        $this->doNotRenderHeader = true; /* i want this to be an ajax request*/
		$this->set('isAjax', $this->doNotRenderHeader);

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

        $values = array(mysql_real_escape_string($entry['Entry']['id']), mysql_real_escape_string($entry['Entry']['time']), mysql_real_escape_string($entry['Entry']['title']), mysql_real_escape_string($entry['Entry']['entry']));
        $fields = array('id','time','title','entry');

        select_db();
		// doesn't work if the ID is the same, and there is already a row in the table for this ID
		// so it won't save 2nd and subsequent edits if the title is the same
        $result = insert_record('deleted_blog', $fields, $values) || update_record('deleted_blog', $fields, $values, mysql_real_escape_string($entry['Entry']['id']));
    
        // now delete from main blog table
        $this->doNotRenderHeader = true;
		$this->set('isAjax', $this->doNotRenderHeader);
        $this->Entry->id = $_POST['id'];
        $this->Entry->delete();
		
//		mysql_close(); // do I need this?
    }
  
    function update() {
        $this->doNotRenderHeader = true;
		$this->set('isAjax', $this->doNotRenderHeader);
        $this->Entry->id = $_POST['id'];
        $entry = $this->Entry->search();
      
        $fields = array('id','time','title','entry');

        $oldValues = array(mysql_real_escape_string($entry['Entry']['id']), mysql_real_escape_string($entry['Entry']['time']), mysql_real_escape_string($entry['Entry']['title']), mysql_real_escape_string($entry['Entry']['entry']));

        select_db();
        // if the ID is the same, and there is already a row in the table for this ID
		// it won't save 2nd and subsequent edits if the title is the same, so update if insert fails
		$result = insert_record('deleted_blog', $fields, $oldValues) || update_record('deleted_blog', $fields, $oldValues, mysql_real_escape_string($entry['Entry']['id']));
		
        $newTime = (isset($_POST['time']))? mysql_real_escape_string($_POST['time']) : $entry['Entry']['time'];
        $newTitle = (isset($_POST['title']))? mysql_real_escape_string($_POST['title']) : $entry['Entry']['title'];
        $newEntry = (isset($_POST['entry']))? mysql_real_escape_string($_POST['entry']) : $entry['Entry']['entry'];

        $oldIdArray = explode("/", $entry['Entry']['id']);
        $oldYear = $oldIdArray[0];
        $oldMonth = $oldIdArray[1];
        $oldDate = $oldIdArray[2];        
        $newId = create_id($newTitle, $oldYear, $oldMonth, $oldDate);
        
        $newValues = array($newId, $newTime, $newTitle, $newEntry);
      
        update_record('blog', $fields, $newValues, mysql_real_escape_string($entry['Entry']['id'])); // also updates tags and categories
        mysql_close();
    }
}
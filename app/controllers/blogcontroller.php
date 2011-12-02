<?php

class BlogController extends Controller {

// first item in $queryArray must be 0 or 1, for whether to render header

    function id($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
        $this->Entry->regexp('id',implode("/", $queryArray));
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }

    function tag($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
        $ids = rtrv_ids_by_tag($queryArray);
        $this->Entry->in('id',implode("','",$ids));
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }
    
    function category($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
		
        $ids = rtrv_ids_by_category(str_replace("_", " ",$queryArray[0]));
        $this->Entry->in('id',implode("','",$ids));
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }
    
    function index() {
        $this->doNotRenderHeader = true;
		$this->Entry->setPage(1);
        $this->Entry->setLimit(3);
        $this->Entry->orderBy('time','DESC');
        $this->set('blog', $this->Entry->search());
    }

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
//         mysql_close(); // the next lines to delete() will fail if mysql_close() runs
    
        // now delete from main blog table
        $this->doNotRenderHeader = true;
        $this->Entry->id = $_POST['id'];
        $this->Entry->delete();
    }
  
    function update() {
        $this->doNotRenderHeader = true;
        $this->Entry->id = $_POST['id'];
        $entry = $this->Entry->search();
      
        $fields = array('id','time','title','entry');

        $oldValues = array($entry['Entry']['id'], $entry['Entry']['time'], $entry['Entry']['title'], $entry['Entry']['entry']);

        select_db();
        //doesn't work sometimes
        insert_record('deleted_blog', $fields, $oldValues);

        $newTime = (isset($_POST['time']))? mysql_real_escape_string($_POST['time']) : $entry['Entry']['time'];
        $newTitle = (isset($_POST['title']))? mysql_real_escape_string($_POST['title']) : $entry['Entry']['title'];
        $newEntry = (isset($_POST['entry']))? mysql_real_escape_string($_POST['entry']) : $entry['Entry']['entry'];

        $oldIdArray = explode("/", $entry['Entry']['id']);
        $oldYear = $oldIdArray[0];
        $oldMonth = $oldIdArray[1];
        $oldDate = $oldIdArray[2];        
        $newId = create_id($newTitle, $oldYear, $oldMonth, $oldDate);
        
        $newValues = array($newId, $newTime, $newTitle, $newEntry);
      
        update_record($fields, $newValues, $entry['Entry']['id']); // also updates tags and categories
        mysql_close();
    }
}
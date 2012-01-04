<?php

class BlogController extends Controller {

// first item in $queryArray must be 0 or 1, for whether to render header
// set to 0 in routing.php
// not set to 1 anywhere as of 1 dec 11

    function id($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
		
		if ($queryArray[count($queryArray)-2] != 'page') {
			array_push($queryArray, 'page', '1');
		}
		$this->Entry->setPage($queryArray[count($queryArray)-1]);
		$this->Entry->setLimit('5');
		
		$this->Entry->regexp('id',implode("/", array_slice($queryArray, 0, -2)));
		$this->Entry->orderBy('time','DESC');
		$this->Entry->showHMABTM();
		$data = $this->Entry->search();
		$this->set('blog', $data);
		
		$totalPages = $this->Entry->totalPages();
		$this->set('totalPages',$totalPages);
		$this->set('currentPageNumber',$queryArray[count($queryArray)-1]);
		$queryString = implode("/", array_slice($queryArray, 0, -2));
		if (substr($queryString, -1) == '/') $queryString = substr($queryString, 0, -1);
		$this->set('url', $queryString);
    }
    
    function category($queryArray) {
        $this->doNotRenderHeader = $queryArray[0]; // set to '0' in routing.php
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
		
		if (count($queryArray)<2 || $queryArray[count($queryArray)-2] != 'page') {
			array_push($queryArray, 'page', '1');
		}
		$this->Entry->setPage($queryArray[count($queryArray)-1]);
		$this->Entry->setLimit('5');
		
        $this->Entry->where('category',str_replace("_", " ",$queryArray[0]));
        $this->Entry->orderBy('time','DESC');
		$this->Entry->showHMABTM();
		$data = $this->Entry->search();
        $this->set('blog', $data);
		
		$totalPages = $this->Entry->totalPages();
		$this->set('totalPages',$totalPages);
		$this->set('currentPageNumber',$queryArray[count($queryArray)-1]);
		$queryArray = array_slice($queryArray, 0, -2);
		array_unshift($queryArray, 'category');
		$this->set('url', implode("/", $queryArray));
    }
    
    function index($queryArray) {
        $this->doNotRenderHeader = $queryArray[0];
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
		$this->Entry->setPage(1);
        $this->Entry->setLimit(1);
        $this->Entry->orderBy('time','DESC');
		$this->Entry->showHMABTM();
        $data = $this->Entry->search();
        $this->set('blog', $data);
    }
	
	function findBlogTagsForEntries($args) {
		$this->doNotRenderHeader = true; /* i want this to be an ajax request*/
		$this->set('isAjax', $this->doNotRenderHeader);
		
		$this->Entry->setPage($args['currentPageNumber']);
		$this->Entry->setLimit('5');

		$entryIdList = implode("','", $args['entryIds']);
		$this->Entry->in('id', $entryIdList);
		$this->Entry->orderBy('time','DESC');
		$this->Entry->showHMABTM();
        $data = $this->Entry->search();
		
		$totalPages = $this->Entry->totalPages();

		return array('data' => $data, 'totalPages' => $totalPages);
	}
	
    function add() {
        $this->doNotRenderHeader = true; /* i want this to be an ajax request*/
		$this->set('isAjax', $this->doNotRenderHeader);

		if (isset($_POST['id'])) {
			$this->Entry->id = $_POST['id'];
			$entry = $this->Entry->search(); // clears the object
			if (mysql_real_escape_string($_POST['title']) == mysql_real_escape_string($entry['Entry']['title'])) {
				// if title is the same, just update entry
				$upd = true;
				$this->Entry->id = $_POST['id'];
				$this->Entry->time = $entry['Entry']['time'];
				
			} else {
				// if title is different, save data and delete old entry
				$this->Entry->id = $_POST['id'];
				$this->Entry->delete(); // clears the object
				// add new entry
				$upd = false;
				$oldIdArray = explode("/", $entry['Entry']['id']);
				$oldYear = $oldIdArray[0];
				$oldMonth = $oldIdArray[1];
				$oldDate = $oldIdArray[2];
				$this->Entry->id = create_id($_POST['title'], $oldYear, $oldMonth, $oldDate);
				$this->Entry->time = $entry['Entry']['time'];
			}
		} else {
			// new entry only
			$upd = false;
			$this->Entry->id = create_id($_POST['title'], $_POST['year'], $_POST['month'], $_POST['date']);
			$this->Entry->time = $_POST['time'];
		}

		$this->Entry->category = $_POST['category'];
		$this->Entry->title = $_POST['title'];
		$this->Entry->entry = $_POST['entry'];
		$this->Entry->save($upd); // clears the object
	}

    function delete() {
        $this->doNotRenderHeader = true;
		$this->set('isAjax', $this->doNotRenderHeader);
        $this->Entry->id = $_POST['id'];
        $this->Entry->delete();
    }
}
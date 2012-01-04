<?php

class TagsController extends Controller {

	# gets all the entries for a tag
    function view_by_tag($queryArray) {
	    $this->doNotRenderHeader = $queryArray[0]; # set to '0' in routing.php
		array_shift($queryArray);
		$this->set('isAjax', $this->doNotRenderHeader);
		
		# get the id for the tag_nm in the url
		$this->Tag->where('tag_nm', $queryArray[0]);
		$tagInfo = $this->Tag->search();
		$id = $tagInfo[0]['Tag']['id'];
		
		# get the entries for this tag id
        $this->Tag->id = $id;
		$this->Tag->showHMABTM();
		$data = $this->Tag->search();
        //$this->set('blog', $data['Entry']);
		$this->set('tag', $data['Tag']);
		
		# build array of entryIds for this tag
		$entryIds = array();
		foreach ($data['Entry'] as $entry) {
			array_push($entryIds, $entry['Entry']['id']);
		}
		
		# set the current page number
		if (count($queryArray)<2 || $queryArray[count($queryArray)-2] != 'page') {
			$currentPageNumber = 1;
		} else {
			$currentPageNumber = $queryArray[count($queryArray)-1];
			$queryArray = array_slice($queryArray, 0, -2);
		}		
		
		# get blog data
		$data2 = performAction('blog','findBlogTagsForEntries',array('entryIds'=>$entryIds,'currentPageNumber'=>$currentPageNumber));
		
		$this->set('totalPages',$data2['totalPages']);
		$this->set('currentPageNumber',$currentPageNumber);
		array_unshift($queryArray, 'tag');
		$this->set('url', implode("/", $queryArray));
		$this->set('blog', $data2['data']);
		
		#print_r($data); 
		#Array ( [Tag] => 
		#						Array ( [id] => 37
		#									[tag_nm] => yoga )
		#			[Entry] => 
		#						Array ( [0] =>
		#										Array ( [Entry] =>
		#																Array ( [id] => 2011/02/01/clarity
		#																			[time] => blah
		#																			[title] => Clarity
		#																			[entry] = > blah
		#																			[category] => yoga )
		#													[blog_tags] => 
		#																Array ( [entry_id] => 2011/02/01/clarity
		#																			[tag_id] => 37 ) )
		#									[1] =>
		#										Array ( [Entry] =>
		#																Array ( [id] => 2011/12/05/teaching
		#																			[time] => blah
		#																			[title] => Teaching
		#																			[entry] = > blah
		#																			[category] => yoga )
		#													[blog_tags] => 
		#																Array ( [entry_id] => 2011/12/05/teaching
		#																			[tag_id] => 37 ) ) ) )
    }
}
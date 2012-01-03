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
        $this->set('blog', $data['Entry']);
		$this->set('tag', $data['Tag']);
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
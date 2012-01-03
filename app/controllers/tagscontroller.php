<?php

class TagsController extends Controller {

    function view($queryArray) {
        $this->Tag->id = $queryArray[1];
		$this->Tag->showHMABTM();
        $this->set('data', $this->Tag->search());
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
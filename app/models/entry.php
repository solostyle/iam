<?php

class Entry extends Model {

	var $hasManyAndBelongsToMany = array('Tag' => 'Tag');
}

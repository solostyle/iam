<?php

class Tag extends Model {

	var $hasManyAndBelongsToMany = array('Entry' => 'Entry');
}

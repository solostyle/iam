<?php

class ArchmenuController extends Controller {
  
  // Use this when you need to update the javascript object (!)
  function save($isAjaxR=true) {
    if ($isAjaxR) $this->doNotRenderHeader = true;
    $this->set('menu', json_encode($this->create_archive_nav_array()));
  }
  
  // Use in header.php
  // Currently does not render because only called through performAction()
  function index($isAjaxR=true) {
    if ($isAjaxR) $this->doNotRenderHeader = true;
    return $this->create_archive_nav_menu($this->create_archive_nav_array());
  }
  
  // Creates the archive navigation menu
  function create_archive_nav_menu($arr) {
    // start the html
    $html = '<div id="archmenuWP">';
    $html .= '<ul class="archlev1 archmenu_list_years" id="archmenu">';
    $years = array_keys($arr);
	  
    foreach($years as $y) {
	    
      $html .= '<li id="archmenu_li_y_' . $y . '">';
      $html .= make_link($y . ' (' . $arr[$y]['count'] . ')', make_url($y.'/'));
      $html .= '<span class="archmenu_ty archToggleButton" id="archmenu_ty_' . $y . '">+</span>'; // handle clicks with JS
      $html .= '</li>';
      unset($arr[$y]['count']);
	    
      $months = array_keys($arr[$y]);
      $html .= '<ul class="archlev2 archmenu_list_months hidden" id="archmenu_y_' . $y . '">';
	    
      foreach($months as $m) {
	$html .= '<li id="archmenu_li_y_' . $y . '_m_' . $m . '">';
	$html .= make_link(monthname($m) . ' (' . $arr[$y][$m]['count'] . ')', make_url($y.'/'.$m.'/'));
	$html .= '<span class="archmenu_tm archToggleButton" id="archmenu_ty_' . $y . '_tm_' . $m . '">+</span>'; // handle clicks with JS
	$html .= '</li>';
	unset($arr[$y][$m]['count']);
	      
	$entries = $arr[$y][$m];
	$html .= '<ul class="archlev3 archmenu_list_titles hidden" id="archmenu_y_' . $y . '_m_' . $m . '">';
	foreach($entries as $id => $entry) {
		
	  $html .= '<li id="archmenu_li_id_' . $id . '">';
	  $html .= make_link($entry['title'], make_url($id));
	  $html .= '</li>';
	}
	$html .= '</ul>';
      }
      $html .= '</ul>';
    }
    $html .= '</ul></div>';
    return $html;
  }

  // returns a multidimensional array of 
  // year->count, year->months, month->count, month->titles, titles->id->title
  function create_archive_nav_array() {
    $start_and_end_dates = blog_first_and_last_dates();
    $start_date = $start_and_end_dates[0];
    $end_date = $start_and_end_dates[1];
    $start_year = strftime("%Y", strtotime($start_date));
    $end_year = strftime("%Y", strtotime($end_date));
    $titles_counts_array = array();
	  
    // for expand/collapse
    $now  = my_time();
    $now_year = date('Y', $now);
    $now_month= date('m', $now);
	  
    // build the array
    for($y=$end_year;$y>=$start_year;$y--) {
      $num_rows_in_year = count(rtrv_titles($y));
	    
      if ($num_rows_in_year) {
	      
	$titles_counts_array[$y] = array();
	$titles_counts_array[$y]['count'] = $num_rows_in_year;
	      
	for($m='12';$m>='1';$m--) {
	  if ($m<='9') {
	    $m = '0' . $m;
	  }
	  $ids_titles = rtrv_titles($y . '/' . $m);
	  $num_rows_in_month = count($ids_titles);
		
	  if ($num_rows_in_month) {
		  
	    $titles_counts_array[$y][$m] = array();
	    $titles_counts_array[$y][$m]['count'] = $num_rows_in_month;
		  
	    foreach($ids_titles as $id_title) {
	      $id = $id_title[0];
	      $title = $id_title[1];
	      $titles_counts_array[$y][$m][$id] = array();
	      $titles_counts_array[$y][$m][$id]['title'] = $title;
	    }
	  }
	}
      }
    }
	  
    return $titles_counts_array;
  }
	
	
}
<?

	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
include_once 'inc/header.php'; // why is this here?



$markup_left = 

'<div id="left">

	<div style="clear:both">
		<h3>The Archives</h3>';

select_db($s, $u, $p, $db);
	
// display a form to allow the user to choose a range of entries
$markup_left .= '<div style="margin:5px 5px 0 5px;padding-bottom:5px">
	<form method="post" action="http://' . $_SERVER['HTTP_HOST'] . '/archive.php">';
$markup_left .= '<p>From ';
$markup_left .= list_months("start_month",$_SESSION['arch_start_month']);
$markup_left .= '&nbsp;';
$markup_left .= list_years("start_year",$_SESSION['arch_start_year']);
$markup_left .= '</p>';
$markup_left .= '<p>until ';
$markup_left .= list_months("end_month",$_SESSION['arch_end_month']);
$markup_left .= '&nbsp;';
$markup_left .= list_years("end_year",$_SESSION['arch_end_year']);
$markup_left .= '</p>';

// allow user to preview or view full
				
$markup_left .= '<p><input type="radio" name="view_typ" value="preview"';
		if ( ($_SESSION['arch_view_typ']=='preview') | (!isset($_SESSION['arch_view_typ'])) )
			$markup_left .= ' checked="checked"';
$markup_left .= ' />Preview &nbsp;
<input type="radio" name="view_typ" value="full"';
		if ($_SESSION['arch_view_typ']=='full')
			$markup_left .= ' checked="checked"';
$markup_left .= ' />Full &nbsp; 
	<input type="submit" name="submit" value="Go" /></p>
	</form></div>';


mysql_close();
	
$markup_left .= '
	</div>

</div><!-- /end #left -->';

print $markup_left;

?>

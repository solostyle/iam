<?

	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
include_once 'inc/header.php'; // why is this here?



$markup_left = 

'<div id="left">

		<h3>archives</h3>';

select_db($s, $u, $p, $db);

$years = list_years();
$years_links = '';

foreach($years as $year) {
  $years_links .= make_link($year, make_url($year));
  $years_links .= '<br />';
}

$markup_left .= '<div>' . $years_links . '</div>';


mysql_close();
	
$markup_left .= '
</div><!-- /end #left -->';

print $markup_left;

?>

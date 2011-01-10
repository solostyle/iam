<?

	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';


$markup_left = 

'<div id="left">

		<h3>archives</h3>';

select_db();

$archive_nav_array = create_archive_nav_array();
$archive_nav_menu = create_archive_nav_menu($archive_nav_array);

$markup_left .= '<div>' . $archive_nav_menu . '</div>';


mysql_close();
	
$markup_left .= '
</div><!-- /end #left -->';

print $markup_left;

?>

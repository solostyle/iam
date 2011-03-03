<?php
session_start();
include_once 'dropcopyfunc.php' ;

print '<html><head><title>blah</title></head>
	<body>
	<form action="drop-confirm.php" name="tableForm" method="post">
	<input type="text" name="year" size="10" /> <br />
	<input type="radio" name="func" value="copy" /> Copy <br />
	<input type="radio" name="func" value="drop" /> Drop <br />
	<input type="submit" value="submit" name="submit" />
	</form>
	</body>
	</html>';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
<link rel="stylesheet" href="heating.css">
<title>Heizung</title>
  </head>
  <body>
<div align="center">
<?php
echo "<img src=\"energy_bar.php?interval=" . $_GET["interval"]. "&daystart=" . 
	$_GET["daystart"] . "&daystop=" . $_GET["daystop"] . "&monthstart=" . 
	$_GET["monthstart"] . "&monthstop=" . $_GET["monthstop"] . "&yearstart=" . 
	$_GET["yearstart"] ."&yearstop=" . $_GET["yearstop"] . "\"<br><hr><br>";
echo "<img src=\"energy_pie.php?interval=" . $_GET["interval"]. "&daystart=" . 
	$_GET["daystart"] . "&daystop=" . $_GET["daystop"] . "&monthstart=" . 
	$_GET["monthstart"] . "&monthstop=" . $_GET["monthstop"] . "&yearstart=" . 
	$_GET["yearstart"] ."&yearstop=" . $_GET["yearstop"] . "\"";

?>
</div>
  </body>
</html>

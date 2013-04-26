<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
<link rel="stylesheet" href="heating.css">
<title>Heizung</title>
  </head>
  <body>
<div align="center">
<?php

/*first fetch values*/
exec("./vclient -h 127.0.0.1:3003 -c 'getBetriebArtM1','getBetriebArtM2' ", $output , $retval);
//var_dump($output);

/*change values if needed*/
if ($_GET["submitted"]){
	echo "Configured<br>\n";
	if ($output[1] != $_GET["betriebszustandm1"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setBetriebArtM1 ". $_GET["betriebszustandm1"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[1] = $_GET["betriebszustandm1"];
		}
	}
	if ($output[3] != $_GET["betriebszustandm2"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setBetriebArtM2 ". $_GET["betriebszustandm2"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[3] = $_GET["betriebszustandm2"];
		}
	}
}

echo "<form>\n";
echo "<table><tr><td>";
echo "Betriebszustand M1<br>\n";
echo "</td><td>";
echo "Betriebszustand M2<br>\n";
echo "</td></tr><tr><td>";
echo "<input type=\"radio\" name=\"betriebszustandm1\" value=\"WW\" ";
if ($output[1] == "WW") echo "checked";
echo ">nur Warmwasser<br>\n";
echo "</td><td>";
echo "<input type=\"radio\" name=\"betriebszustandm2\" value=\"WW\" ";
if ($output[3] == "WW") echo "checked";
echo ">nur Warmwasser<br>\n";
echo "</td></tr><tr><td>";
echo "<input type=\"radio\" name=\"betriebszustandm1\" value=\"RED\"";
if ($output[1] == "RED") echo "checked";
echo ">reduziert<br>\n";
echo "</td><td>";
echo "<input type=\"radio\" name=\"betriebszustandm2\" value=\"RED\"";
if ($output[3] == "RED") echo "checked";
echo ">reduziert<br>\n";
echo "</td></tr><tr><td>";
echo "<input type=\"radio\" name=\"betriebszustandm1\" value=\"NORM\"";
if ($output[1] == "NORM") echo "checked";
echo ">Normal<br>\n";
echo "</td><td>";
echo "<input type=\"radio\" name=\"betriebszustandm2\" value=\"NORM\"";
if ($output[3] == "NORM") echo "checked";
echo ">Normal<br>\n";
echo "</td></tr><tr><td>";
echo "<input type=\"radio\" name=\"betriebszustandm1\" value=\"H+WW\"";
if ($output[1] == "H+WW") echo "checked";
echo ">Heizung und Warmwasser<br>\n";
echo "</td><td>";
echo "<input type=\"radio\" name=\"betriebszustandm2\" value=\"H+WW\"";
if ($output[3] == "H+WW") echo "checked";
echo ">Heizung und Warmwasser<br>\n";
echo "</td></tr><tr><td>";
echo "<input type=\"radio\" name=\"betriebszustandm1\" value=\"ABSCHALT\"";
if ($output[1] == "ABSCHALT") echo "checked";
echo ">abgeschaltet<br>\n";
echo "</td><td>";
echo "<input type=\"radio\" name=\"betriebszustandm2\" value=\"ABSCHALT\"";
if ($output[3] == "ABSCHALT") echo "checked";
echo ">abgeschaltet<br>\n";
echo "</td></tr><tr><td>";
echo "<input type=\"hidden\" name=\"submitted\" value=\"1\">\n";
echo "<input type=\"submit\" value=\"OK\">\n";
echo "<form>\n";
?>
</div>
  </body>
</html>

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
exec("./vclient -h 127.0.0.1:3003 -c 'getBetriebArtM1','getBetriebArtM2','getTempWWsoll','getPumpeStatusZirku','getNeigungM1','getNeigungM2','getNiveauM1','getNiveauM2'", $output , $retval);
if ($retval != 0) echo "VCONTROL BUSY AT THE MOMENT!<br>\n";
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
	if ($output[5] != $_GET["tempwwsoll"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setTempWWsoll ". $_GET["tempwwsoll"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[5] = $_GET["tempwwsoll"];
		}
	}
	if ($output[7] != $_GET["zirkulationspumpe"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setPumpeStatusZirku ". $_GET["zirkulationspumpe"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[7] = $_GET["zirkulationspumpe"];
		}
	}
	if ($output[9] != $_GET["neigungm1"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setNeigungM1 ". $_GET["neigungm1"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[9] = $_GET["neigungm1"];
		}
	}
	if ($output[11] != $_GET["neigungm2"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setNeigungM2 ". $_GET["neigungm2"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[11] = $_GET["neigungm2"];
		}
	}
	if ($output[13] != $_GET["niveaum1"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setNiveauM1 ". $_GET["niveaum1"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[13] = $_GET["niveaum1"];
		}
	}
	if ($output[15] != $_GET["niveaum2"]){
		exec("./vclient -h 127.0.0.1:3003 -c 'setNiveauM2 ". $_GET["niveaum2"] ."' ", $write_output , $retval);
		if ($retval == 0){
			$output[15] = $_GET["niveaum2"];
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
echo "</td></tr></table><br><br>";
echo "<table><tr><td>";
echo "Zirkulationspumpe: ";
echo "<input type=\"radio\" name=\"zirkulationspumpe\" value=\"1\"";
if ($output[7] == 1) echo "checked";
echo ">an \n";
echo "<input type=\"radio\" name=\"zirkulationspumpe\" value=\"0\"";
if ($output[7] == 0) echo "checked";
echo ">aus";
echo "</td></tr></table><br><br>\n";
$tempwwsoll = round($output[5]);
$neigungm1 = round($output[9],1);
$neigungm2 = round($output[11],1);
$niveaum1 = round($output[13]);
$niveaum2 = round($output[15]);
echo "<table><tr><td>";
echo "Solltemperatur Warmwasser: ";
echo "<input type=\"text\" name=\"tempwwsoll\" id=\"tempwwsoll\" value=\"". $tempwwsoll . "\" size=\"2\"><br>";
echo "</td><td>";
echo "Neigung M1: ";
echo "<input type=\"text\" name=\"neigungm1\" id=\"neigungm1\" value=\"". $neigungm1 . "\" size=\"2\"><br>";
echo "</td><td>";
echo "Neigung M2: ";
echo "<input type=\"text\" name=\"neigungm2\" id=\"neigungm2\" value=\"". $neigungm2 . "\" size=\"2\"><br>";
echo "</td><td>";
echo "Niveau M1: ";
echo "<input type=\"text\" name=\"niveaum1\" id=\"niveaum1\" value=\"". $niveaum1 . "\" size=\"2\"><br>";
echo "</td><td>";
echo "Niveau M2: ";
echo "<input type=\"text\" name=\"niveaum2\" id=\"niveaum2\" value=\"". $niveaum2 . "\" size=\"2\"><br>";
echo "</td><td>";
echo "</td></tr></table><br><br>";
echo "<input type=\"hidden\" name=\"submitted\" value=\"1\">\n";
echo "<input type=\"submit\" value=\"OK\">\n";
echo "<form>\n";
?>
</div>
  </body>
</html>

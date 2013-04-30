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
exec("./vclient -h 127.0.0.1:3003 -c 'getTimerWWMo','getTimerWWDi','getTimerWWMi','getTimerWWDo','getTimerWWFr','getTimerWWSa','getTimerWWSo'", $output , $retval);

if ($retval != 0) echo "VCONTROL BUSY AT THE MOMENT!<br>\n";
//var_dump($output);
/*change values if needed*/
if ($_GET["submitted"] && $_GET["code"] == 4710){
	echo "Configured<br>\n";
	$i = 1;
	if (substr($output[$i], 5, 5) != $_GET["mo11"] ||
	    substr($output[$i++], 16, 5) != $_GET["mo12"] ||
	    substr($output[$i], 5, 5) != $_GET["mo21"] ||
	    substr($output[$i++], 16, 5) != $_GET["mo22"] ||
	    substr($output[$i], 5, 5) != $_GET["mo31"] ||
	    substr($output[$i++], 16, 5) != $_GET["mo32"] ||
	    substr($output[$i], 5, 5) != $_GET["mo41"] ||
	    substr($output[$i++], 16, 5) != $_GET["mo42"])
	{
	    $mo11 = (strcmp($_GET["mo11"], "--   ") ? $_GET["mo11"] : "");
	    $mo12 = (strcmp($_GET["mo12"], "--") ? $_GET["mo12"] : "");
	    $mo21 = (strcmp($_GET["mo21"], "--   ") ? $_GET["mo21"] : "");
	    $mo22 = (strcmp($_GET["mo22"], "--") ? $_GET["mo22"] : "");
	    $mo31 = (strcmp($_GET["mo31"], "--   ") ? $_GET["mo31"] : "");
	    $mo32 = (strcmp($_GET["mo32"], "--") ? $_GET["mo32"] : "");
	    $mo41 = (strcmp($_GET["mo41"], "--   ") ? $_GET["mo41"] : "");
	    $mo42 = (strcmp($_GET["mo42"], "--") ? $_GET["mo42"] : "");
	    exec("./vclient -h 127.0.0.1:3003 -c 'setTimerWWMo " . $mo11 ." ". $mo12 . " ". $mo21 ." ". $mo22 . " ". $mo31 ." ". $mo32 . " ". $mo41 ." ". 		$mo42 . "'", $write_output , $retval);
		if ($retval == 0){
			//$output[1] = $_GET["niveaum1"];
		}
	}
	$i++;
	if (substr($output[$i], 5, 5) != $_GET["di11"] ||
	    substr($output[$i++], 16, 5) != $_GET["di12"] ||
	    substr($output[$i], 5, 5) != $_GET["di21"] ||
	    substr($output[$i++], 16, 5) != $_GET["di22"] ||
	    substr($output[$i], 5, 5) != $_GET["di31"] ||
	    substr($output[$i++], 16, 5) != $_GET["di32"] ||
	    substr($output[$i], 5, 5) != $_GET["di41"] ||
	    substr($output[$i++], 16, 5) != $_GET["di42"])
	{
	    $di11 = (strcmp($_GET["di11"], "--   ") ? $_GET["di11"] : "");
	    $di12 = (strcmp($_GET["di12"], "--") ? $_GET["di12"] : "");
	    $di21 = (strcmp($_GET["di21"], "--   ") ? $_GET["di21"] : "");
	    $di22 = (strcmp($_GET["di22"], "--") ? $_GET["di22"] : "");
	    $di31 = (strcmp($_GET["di31"], "--   ") ? $_GET["di31"] : "");
	    $di32 = (strcmp($_GET["di32"], "--") ? $_GET["di32"] : "");
	    $di41 = (strcmp($_GET["di41"], "--   ") ? $_GET["di41"] : "");
	    $di42 = (strcmp($_GET["di42"], "--") ? $_GET["di42"] : "");
	    exec("./vclient -h 127.0.0.1:3003 -c 'setTimerWWDi " . $di11 ." ". $di12 . " ". $di21 ." ". $di22 . " ". $di31 ." ". $di32 . " ". $di41 ." ". 		$di42 . "'", $write_output , $retval);
		if ($retval == 0){
			//$output[$i] = $_GET["niveaum1"];
		}
	}
	$i++;
	if (substr($output[$i], 5, 5) != $_GET["mi11"] ||
	    substr($output[$i++], 16, 5) != $_GET["mi12"] ||
	    substr($output[$i], 5, 5) != $_GET["mi21"] ||
	    substr($output[$i++], 16, 5) != $_GET["mi22"] ||
	    substr($output[$i], 5, 5) != $_GET["mi31"] ||
	    substr($output[$i++], 16, 5) != $_GET["mi32"] ||
	    substr($output[$i], 5, 5) != $_GET["mi41"] ||
	    substr($output[$i++], 16, 5) != $_GET["mi42"])
	{
	    $mi11 = (strcmp($_GET["mi11"], "--   ") ? $_GET["mi11"] : "");
	    $mi12 = (strcmp($_GET["mi12"], "--") ? $_GET["mi12"] : "");
	    $mi21 = (strcmp($_GET["mi21"], "--   ") ? $_GET["mi21"] : "");
	    $mi22 = (strcmp($_GET["mi22"], "--") ? $_GET["mi22"] : "");
	    $mi31 = (strcmp($_GET["mi31"], "--   ") ? $_GET["mi31"] : "");
	    $mi32 = (strcmp($_GET["mi32"], "--") ? $_GET["mi32"] : "");
	    $mi41 = (strcmp($_GET["mi41"], "--   ") ? $_GET["mi41"] : "");
	    $mi42 = (strcmp($_GET["mi42"], "--") ? $_GET["mi42"] : "");
	    exec("./vclient -h 127.0.0.1:3003 -c 'setTimerWWMi " . $mi11 ." ". $mi12 . " ". $mi21 ." ". $mi22 . " ". $mi31 ." ". $mi32 . " ". $mi41 ." ". 		$mi42 . "'", $write_output , $retval);
		if ($retval == 0){
			//$output[$i] = $_GET["niveaum1"];
		}
	}
	$i++;
	if (substr($output[$i], 5, 5) != $_GET["do11"] ||
	    substr($output[$i++], 16, 5) != $_GET["do12"] ||
	    substr($output[$i], 5, 5) != $_GET["do21"] ||
	    substr($output[$i++], 16, 5) != $_GET["do22"] ||
	    substr($output[$i], 5, 5) != $_GET["do31"] ||
	    substr($output[$i++], 16, 5) != $_GET["do32"] ||
	    substr($output[$i], 5, 5) != $_GET["do41"] ||
	    substr($output[$i++], 16, 5) != $_GET["do42"])
	{
	    $do11 = (strcmp($_GET["do11"], "--   ") ? $_GET["do11"] : "");
	    $do12 = (strcmp($_GET["do12"], "--") ? $_GET["do12"] : "");
	    $do21 = (strcmp($_GET["do21"], "--   ") ? $_GET["do21"] : "");
	    $do22 = (strcmp($_GET["do22"], "--") ? $_GET["do22"] : "");
	    $do31 = (strcmp($_GET["do31"], "--   ") ? $_GET["do31"] : "");
	    $do32 = (strcmp($_GET["do32"], "--") ? $_GET["do32"] : "");
	    $do41 = (strcmp($_GET["do41"], "--   ") ? $_GET["do41"] : "");
	    $do42 = (strcmp($_GET["do42"], "--") ? $_GET["do42"] : "");
	    exec("./vclient -h 127.0.0.1:3003 -c 'setTimerWWDo " . $do11 ." ". $do12 . " ". $do21 ." ". $do22 . " ". $do31 ." ". $do32 . " ". $do41 ." ". 		$do42 . "'", $write_output , $retval);
		if ($retval == 0){
			//$output[$i] = $_GET["niveaum1"];
		}
	}
	$i++;
	if (substr($output[$i], 5, 5) != $_GET["fr11"] ||
	    substr($output[$i++], 16, 5) != $_GET["fr12"] ||
	    substr($output[$i], 5, 5) != $_GET["fr21"] ||
	    substr($output[$i++], 16, 5) != $_GET["fr22"] ||
	    substr($output[$i], 5, 5) != $_GET["fr31"] ||
	    substr($output[$i++], 16, 5) != $_GET["fr32"] ||
	    substr($output[$i], 5, 5) != $_GET["fr41"] ||
	    substr($output[$i++], 16, 5) != $_GET["fr42"])
	{
	    $fr11 = (strcmp($_GET["fr11"], "--   ") ? $_GET["fr11"] : "");
	    $fr12 = (strcmp($_GET["fr12"], "--") ? $_GET["fr12"] : "");
	    $fr21 = (strcmp($_GET["fr21"], "--   ") ? $_GET["fr21"] : "");
	    $fr22 = (strcmp($_GET["fr22"], "--") ? $_GET["fr22"] : "");
	    $fr31 = (strcmp($_GET["fr31"], "--   ") ? $_GET["fr31"] : "");
	    $fr32 = (strcmp($_GET["fr32"], "--") ? $_GET["fr32"] : "");
	    $fr41 = (strcmp($_GET["fr41"], "--   ") ? $_GET["fr41"] : "");
	    $fr42 = (strcmp($_GET["fr42"], "--") ? $_GET["fr42"] : "");
	    exec("./vclient -h 127.0.0.1:3003 -c 'setTimerWWFr " . $fr11 ." ". $fr12 . " ". $fr21 ." ". $fr22 . " ". $fr31 ." ". $fr32 . " ". $fr41 ." ". 		$fr42 . "'", $write_output , $retval);
		if ($retval == 0){
			//$output[$i] = $_GET["niveaum1"];
		}
	}
	$i++;
	if (substr($output[$i], 5, 5) != $_GET["sa11"] ||
	    substr($output[$i++], 16, 5) != $_GET["sa12"] ||
	    substr($output[$i], 5, 5) != $_GET["sa21"] ||
	    substr($output[$i++], 16, 5) != $_GET["sa22"] ||
	    substr($output[$i], 5, 5) != $_GET["sa31"] ||
	    substr($output[$i++], 16, 5) != $_GET["sa32"] ||
	    substr($output[$i], 5, 5) != $_GET["sa41"] ||
	    substr($output[$i++], 16, 5) != $_GET["sa42"])
	{
	    $sa11 = (strcmp($_GET["sa11"], "--   ") ? $_GET["sa11"] : "");
	    $sa12 = (strcmp($_GET["sa12"], "--") ? $_GET["sa12"] : "");
	    $sa21 = (strcmp($_GET["sa21"], "--   ") ? $_GET["sa21"] : "");
	    $sa22 = (strcmp($_GET["sa22"], "--") ? $_GET["sa22"] : "");
	    $sa31 = (strcmp($_GET["sa31"], "--   ") ? $_GET["sa31"] : "");
	    $sa32 = (strcmp($_GET["sa32"], "--") ? $_GET["sa32"] : "");
	    $sa41 = (strcmp($_GET["sa41"], "--   ") ? $_GET["sa41"] : "");
	    $sa42 = (strcmp($_GET["sa42"], "--") ? $_GET["sa42"] : "");
	    exec("./vclient -h 127.0.0.1:3003 -c 'setTimerWWSa " . $sa11 ." ". $sa12 . " ". $sa21 ." ". $sa22 . " ". $sa31 ." ". $sa32 . " ". $sa41 ." ". 		$sa42 . "'", $write_output , $retval);
		if ($retval == 0){
			//$output[$i] = $_GET["niveaum1"];
		}
	}
	$i++;
	if (substr($output[$i], 5, 5) != $_GET["so11"] ||
	    substr($output[$i++], 16, 5) != $_GET["so12"] ||
	    substr($output[$i], 5, 5) != $_GET["so21"] ||
	    substr($output[$i++], 16, 5) != $_GET["so22"] ||
	    substr($output[$i], 5, 5) != $_GET["so31"] ||
	    substr($output[$i++], 16, 5) != $_GET["so32"] ||
	    substr($output[$i], 5, 5) != $_GET["so41"] ||
	    substr($output[$i++], 16, 5) != $_GET["so42"])
	{
	    $so11 = (strcmp($_GET["so11"], "--   ") ? $_GET["so11"] : "");
	    $so12 = (strcmp($_GET["so12"], "--") ? $_GET["so12"] : "");
	    $so21 = (strcmp($_GET["so21"], "--   ") ? $_GET["so21"] : "");
	    $so22 = (strcmp($_GET["so22"], "--") ? $_GET["so22"] : "");
	    $so31 = (strcmp($_GET["so31"], "--   ") ? $_GET["so31"] : "");
	    $so32 = (strcmp($_GET["so32"], "--") ? $_GET["so32"] : "");
	    $so41 = (strcmp($_GET["so41"], "--   ") ? $_GET["so41"] : "");
	    $so42 = (strcmp($_GET["so42"], "--") ? $_GET["so42"] : "");
	    exec("./vclient -h 127.0.0.1:3003 -c 'setTimerWWSo " . $so11 ." ". $so12 . " ". $so21 ." ". $so22 . " ". $so31 ." ". $so32 . " ". $so41 ." ". 		$so42 . "'", $write_output , $retval);
		if ($retval == 0){
			//$output[$i] = $_GET["niveaum1"];
		}
	}

}

echo "<form>\n";
echo "<br>";
echo "<table><tr><td>";
echo "Montag: ";
echo "<table><tr><td>";
$i = 1;
$timer1 = substr($output[$i], 5, 5);
if ($timer1 == "merWW"){
 $i++;
 $timer1 = substr($output[$i], 5, 5);
}
$timer2 = substr($output[$i++], 16, 5);
echo "1: ";
echo "<input type=\"text\" name=\"mo11\" id=\"mo11\" value=\"". $timer1 . "\" size=\"5\"> ";
echo "<input type=\"text\" name=\"mo12\" id=\"mo12\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "2: ";
echo "<input type=\"text\" name=\"mo21\" id=\"mo21\" value=\"". $timer1 . "\" size=\"5\"> ";
echo "<input type=\"text\" name=\"mo22\" id=\"mo22\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "3: ";
echo "<input type=\"text\" name=\"mo31\" id=\"mo31\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"mo32\" id=\"mo32\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "4: ";
echo "<input type=\"text\" name=\"mo41\" id=\"mo41\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"mo42\" id=\"mo42\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr></table>";

echo "</td><td>";
$i++;
echo "Dienstag: ";
echo "<table><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "1: ";
echo "<input type=\"text\" name=\"di11\" id=\"di11\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"di12\" id=\"di12\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "2: ";
echo "<input type=\"text\" name=\"di21\" id=\"di21\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"di22\" id=\"di22\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "3: ";
echo "<input type=\"text\" name=\"di31\" id=\"di31\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"di32\" id=\"di32\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "4: ";
echo "<input type=\"text\" name=\"di41\" id=\"di41\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"di42\" id=\"di42\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr></table>";

echo "</td><td>";
$i++;
echo "Mittwoch: ";
echo "<table><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "1: ";
echo "<input type=\"text\" name=\"mi11\" id=\"mi11\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"mi12\" id=\"mi12\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "2: ";
echo "<input type=\"text\" name=\"mi21\" id=\"mi21\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"mi22\" id=\"mi22\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "3: ";
echo "<input type=\"text\" name=\"mi31\" id=\"mi31\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"mi32\" id=\"mi32\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "4: ";
echo "<input type=\"text\" name=\"mi41\" id=\"mi41\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"mi42\" id=\"mi42\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr></table>";

echo "</td><td>";
$i++;
echo "Donnerstag: ";
echo "<table><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "1: ";
echo "<input type=\"text\" name=\"do11\" id=\"do11\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"do12\" id=\"do12\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "2: ";
echo "<input type=\"text\" name=\"do21\" id=\"do21\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"do22\" id=\"do22\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "3: ";
echo "<input type=\"text\" name=\"do31\" id=\"do31\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"do32\" id=\"do32\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "4: ";
echo "<input type=\"text\" name=\"do41\" id=\"do41\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"do42\" id=\"do42\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr></table>";

echo "</td></tr><tr><td>";
$i++;
echo "Freitag: ";
echo "<table><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "1: ";
echo "<input type=\"text\" name=\"fr11\" id=\"fr11\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"fr12\" id=\"fr12\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "2: ";
echo "<input type=\"text\" name=\"fr21\" id=\"fr21\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"fr22\" id=\"fr22\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "3: ";
echo "<input type=\"text\" name=\"fr31\" id=\"fr31\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"fr32\" id=\"fr32\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "4: ";
echo "<input type=\"text\" name=\"fr41\" id=\"fr41\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"fr42\" id=\"fr42\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr></table>";

echo "</td><td>";
$i++;
echo "Samstag: ";
echo "<table><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "1: ";
echo "<input type=\"text\" name=\"sa11\" id=\"sa11\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"sa12\" id=\"sa12\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "2: ";
echo "<input type=\"text\" name=\"sa21\" id=\"sa21\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"sa22\" id=\"sa22\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "3: ";
echo "<input type=\"text\" name=\"sa31\" id=\"sa31\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"sa32\" id=\"sa32\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "4: ";
echo "<input type=\"text\" name=\"sa41\" id=\"sa41\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"sa42\" id=\"sa42\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr></table>";

echo "</td><td>";
$i++;
echo "Sonntag: ";
echo "<table><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "1: ";
echo "<input type=\"text\" name=\"so11\" id=\"so11\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"so12\" id=\"so12\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "2: ";
echo "<input type=\"text\" name=\"so21\" id=\"so21\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"so22\" id=\"so22\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "3: ";
echo "<input type=\"text\" name=\"so31\" id=\"so31\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"so32\" id=\"so32\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr><tr><td>";
$timer1 = substr($output[$i], 5, 5);
$timer2 = substr($output[$i++], 16, 5);
echo "4: ";
echo "<input type=\"text\" name=\"so41\" id=\"so41\" value=\"". $timer1 . "\" size=\"5\">";
echo "<input type=\"text\" name=\"so42\" id=\"so42\" value=\"". $timer2 . "\" size=\"5\">";
echo "</td></tr></table>";

echo "</td></tr></table><br><br>";
echo "<input type=\"password\" name=\"code\" size=\"4\"><br>\n";
echo "<input type=\"hidden\" name=\"submitted\" value=\"1\">\n";
echo "<input type=\"submit\" value=\"OK\">\n";
echo "<form>\n";

?>
</div>
  </body>
</html>

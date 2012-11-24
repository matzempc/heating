<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<title>Heizung</title>
	<link rel="stylesheet" href="heating.css">
	</head>
	<body marginheight="25" marginwidth="20" topmargin="25" leftmargin="0">
	
<?PHP

	$today = getdate();
	$day = date("j");
    $month = date("n");
    $year =  date("Y");
	echo "<hr><a href=\"main.php?lang={$language}   \" target=\"main\">&Uuml;bersicht</a>";

    echo "<hr><p><b>Temperaturen</b></p>";
    echo "<form action = \"temp.php\" method=\"get\" target=\"main\">";
	echo "<select name=\"type\">";
    echo "<option value=\"1\" selected> Warmwasser";
    echo "<option value=\"2\"> Puffer";
	echo "<option value=\"3\"> Kollektor";
	echo "<option value=\"4\"> Oelkessel";
	echo "<option value=\"5\"> Heizkreise";
	echo "<option value=\"6\"> Hauptheizkreis";
	echo "<option value=\"7\"> Raeume";
	echo "<option value=\"8\"> Heizkoerper";
    echo "</select><br>";
    echo "<select name=\"daystart\">";

    for($i=1; $i<=31; $i++)
    {
        if($i == $today['mday'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
	echo "</select><br>";
    echo "<select name=\"daystop\">";

    for($i=1; $i<=31; $i++)
    {
       if($i == $today['mday'])
       {
           echo "<option value=\"$i\" selected> $i";
       }
       else
       {
           echo "<option value=\"$i\"> $i";
       }
    }

    echo "</select>.";
    echo "<select name=\"monthstop\">";

    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }

    echo "</select>.";
    echo "<select name=\"yearstop\">";

    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";

/*
	echo "<hr><p><b>Monat</b></p>";
    echo "<form action = \"temp.php\" method=\"get\" target=\"main\">";
	echo "<select name=\"type\">";
    echo "<option value=\"1\" selected> Warmwasser";
    echo "<option value=\"2\"> Puffer";
	echo "<option value=\"3\"> Kollektor";
	echo "<option value=\"4\"> Oelkessel";
	echo "<option value=\"5\"> Heizkreis";

    echo "</select><br>";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"2\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";
*/

	echo "<hr><p><b>Relais</b></p>";
    echo "<form action = \"relais.php\" method=\"get\" target=\"main\">";
	echo "<select name=\"type\">";
    echo "<option value=\"1\" selected> Solar";
    echo "<option value=\"2\"> Oelheizung";

    echo "</select><br>";
    echo "<select name=\"daystart\">";

    for($i=1; $i<=31; $i++)
    {
        if($i == $today['mday'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
	echo "</select><br>";
    echo "<select name=\"daystop\">";

    for($i=1; $i<=31; $i++)
    {
       if($i == $today['mday'])
       {
           echo "<option value=\"$i\" selected> $i";
       }
       else
       {
           echo "<option value=\"$i\"> $i";
       }
    }

    echo "</select>.";
    echo "<select name=\"monthstop\">";

    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }

    echo "</select>.";
    echo "<select name=\"yearstop\">";

    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";

    echo "<hr><p><b>Energie Tag</b></p>";
    echo "<form action = \"energy.php\" method=\"get\" target=\"main\">";
    echo "</select><br>";
    echo "<select name=\"daystart\">";

    for($i=1; $i<=31; $i++)
    {
        if($i == $today['mday'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"1\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";

    echo "<hr><p><b>Energie Monat</b></p>";
    echo "<form action = \"energy.php\" method=\"get\" target=\"main\">";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"2\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";

    echo "<hr><p><b>Energie Jahr</b></p>";
    echo "<form action = \"energy.php\" method=\"get\" target=\"main\">";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"3\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";

    echo "<hr><p><b>Energie Heizsaison</b></p>";
    echo "<form action = \"energy.php\" method=\"get\" target=\"main\">";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=2019; $i++)
    {
		/*TODO add a general include for 7 as July here and in energy_year.php*/
        if(($i == $today['year'] && $today['mon'] > 7) ||
			($i == $today['year']-1 && $today['mon'] <= 7))
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"4\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";


	echo "<hr><p><b>Kesselstarts Tag</b></p>";
    echo "<form action = \"boilerstarts.php\" method=\"get\" target=\"main\">";
    echo "<select name=\"daystart\">";

    for($i=1; $i<=31; $i++)
    {
        if($i == $today['mday'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
	echo "</select><br>";
    echo "<select name=\"daystop\">";

    for($i=1; $i<=31; $i++)
    {
       if($i == $today['mday'])
       {
           echo "<option value=\"$i\" selected> $i";
       }
       else
       {
           echo "<option value=\"$i\"> $i";
       }
    }

    echo "</select>.";
    echo "<select name=\"monthstop\">";

    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }

    echo "</select>.";
    echo "<select name=\"yearstop\">";

    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select><br>";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";

    echo "<hr><p><b>Warmwasser Oel Monat</b></p>";
    echo "<form action = \"warmwater2.php\" method=\"get\" target=\"main\">";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"2\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";


/*
    echo "<hr><br>Photovoltaik:<br><p><b>Energie Monat</b></p>";
    echo "<form action = \"energy_photo.php\" method=\"get\" target=\"main\">";
    echo "<select name=\"monthstart\">";
    for($i=1; $i<=12; $i++)
    {
        if($i == $today['mon'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>.";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"2\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";
*/
/*
    echo "<hr><p><b>Energie Jahr</b></p>";
    echo "<form action = \"energy_photo.php\" method=\"get\" target=\"main\">";
    echo "<select name=\"yearstart\">";
    for($i=2009; $i<=$year; $i++)
    {
        if($i == $today['year'])
        {
            echo "<option value=\"$i\" selected> $i";
        }
        else
        {
            echo "<option value=\"$i\"> $i";
        }
    }
    echo "</select>";
	echo "<input type=\"hidden\" name=\"interval\" value=\"3\">";
    echo "<p><input type = \"submit\" value = \"OK\">";
    echo "</form>";
*/
?>

</body>
</html>

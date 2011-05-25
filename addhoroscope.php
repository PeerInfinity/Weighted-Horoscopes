<?php

$horoscope = $_POST['horoscope'];
$password = $_POST['password'];

include 'login.php';

if( $password == "" )
{
?>
New Horoscope:<br/>
<br/>
<form action="addhoroscope.php" method="post">
Password: <input type="text" name="password" />
<br/>
Horoscope:<br/>
<textarea name="horoscope" ROWS=10 COLS=40 />
</textarea>
<br/>
<input type="submit" />
</form> 
<?php
}
else if( strpos($horoscope, $password) !== FALSE )
{
	print "Error:  the horoscope contains the password.";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/addhoroscope.php\">Add another horoscope</a>";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/admin.php\">Index</a>";
}
else if( $password == $FormPassword )
{
	$horoscopeList = str_replace( "\n", "<br/>", $horoscope );
	
	echo "adding horoscopes:<br/>$horoscopeList<br/><br/>";

	$link = mysql_connect($DBUrl,$DBUser,$DBPassword);

	if( !$link )
	{
		die('Could not connect: ' . mysql_error());
	}

	$db_selected = mysql_select_db($database, $link);

	if( !$db_selected )
	{
		die ('Can\'t use database : ' . mysql_error());
	}
	
	$ExplodedHoroscopes = explode("\n", $horoscope);
	
	foreach( $ExplodedHoroscopes as $index => $curHoroscope )
	{
		$trimemdHoroscope = trim($curHoroscope);
		
		if( strlen($trimemdHoroscope) > 0 )
		{
			$query = sprintf(
			"
			INSERT INTO `horoscopes` (
			`ID` ,
			`text`
			)
			VALUES (
			NULL , '%s'
			)
			",
			mysql_real_escape_string($trimemdHoroscope)
			);

			//print "query:<br/>$query<br/>";

			$result = mysql_query($query);

			if( !$result )
			{
				die('Invalid query: ' . mysql_error());
			}

			$query = "SELECT LAST_INSERT_ID()";

			//print "query:<br/>$query<br/>";

			$result = mysql_query($query);

			if( !$result )
			{
				die('Invalid query: ' . mysql_error());
			}

			$data = mysql_fetch_array($result, MYSQL_NUM);

			$NewHoroscopeID = $data[0];

			print "Inserted Horoscope ID $NewHoroscopeID<br/>";
		}
	}
	
	print "Done.";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/addhoroscope.php\">Add another horoscope</a>";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/admin.php\">Index</a>";

	mysql_close($link);
}
else
{
	echo "invalid password.";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/addhoroscope.php\">Add another horoscope</a>";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/admin.php\">Index</a>";
}
?>
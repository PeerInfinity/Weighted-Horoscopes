<?php

$ID = $_POST['ID'];
$password = $_POST['password'];

include 'login.php';

if( $password == "" )
{
?>
Undelete Horoscope:<br/>
<br/>
<form action="undeletehoroscope.php" method="post">
Horoscope ID: <input type="text" name="ID" />
<br/>
Password: <input type="text" name="password" />
<br/>
<input type="submit" />
</form> 
<?php
}
else if( $password == $FormPassword )
{
	echo "undeleting horoscope ID:<br/>$ID<br/>";

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

	$query=sprintf(
	"UPDATE `horoscopes` SET `deleted` = '0' WHERE `horoscopes`.`ID` =%s",
	mysql_real_escape_string($ID)
	);

	$result = mysql_query($query);

	if( !$result )
	{
		die('Invalid query: ' . mysql_error());
	}

	print "Done";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/undeletehoroscope.php\">Undelete another horoscope</a>";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/admin.php\">Index</a>";

	mysql_close($link);

}
else
{
	echo "invalid password.";
}
?>
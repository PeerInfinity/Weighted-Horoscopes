<?php

$password = $_POST['password'];

include 'login.php';

if( $password == "" )
{
?>
<form action="viewdeleted.php" method="post">
Enter Password: <input type="text" name="password" />
<br/>
<input type="submit" />
</form> 
<?php
}
else if( $password == $FormPassword )
{
	echo "Deleted Horoscopes By ID:<br/>";
	echo "<br/>";

	include 'login.php';

	$link = mysql_connect($DBUrl,$DBUser,$DBPassword);

	if( !$link )
	{
		die('Could not connect: ' . mysql_error());
	}

	$db_selected = mysql_select_db($database, $link);

	if( !$db_selected )
	{
		die('Can\'t use database : ' . mysql_error());
	}

	$query="SELECT * FROM `horoscopes`";

	$result = mysql_query($query);

	if( !$result )
	{
		die('Invalid query: ' . mysql_error());
	}

	$data = mysql_fetch_array($result, MYSQL_ASSOC);

	while( count($data) > 1 )
	{
		if( $data[deleted] == 1 )
		{
			echo "ID: $data[ID]<br/>";
			echo "horoscope:<br/>$data[text]<br/>";
			echo "<br/>Score: ?<br/><br/>";
		}

		$data = mysql_fetch_array($result, MYSQL_ASSOC);
	}

	mysql_close($link);
}
else
{
	echo "invalid password.";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/viewdeleted.php\">Try again</a>";
	print "<br/>";
	print "<br/>";
	print "<a href=\"$RootURL/admin.php\">Index</a>";
}
?>
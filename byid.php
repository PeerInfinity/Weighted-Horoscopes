<?php
	echo "Horoscopes By ID:<br/>";
	echo "<br/><hr/>";

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
		if( $data[deleted] == 0 )
		{
			echo "ID: $data[ID]<br/>";
			echo "Score: $data[averagescore]<br/>";
			if( $data[instancecount] <= 0 )
			{
				echo "Not used yet..<br/>";
			}
			else if( $data[instancecount] == 1 )
			{
				echo "Used 1 time.<br/>";
			}
			else
			{
				echo "Used $data[instancecount] times.<br/>";
			}
			echo "<br/>Horoscope:<br/>$data[text]";
			echo "<br/><hr/>";
		}

		$data = mysql_fetch_array($result, MYSQL_ASSOC);
	}

	mysql_close($link);
?>
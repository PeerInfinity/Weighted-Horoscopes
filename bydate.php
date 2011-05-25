<?php
	echo "Horoscopes By Date:<br/>";
	echo "<br/><hr/>";

	include 'login.php';
	include 'constants.php';

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

	$query1="SELECT * FROM `instances`";

	$result1 = mysql_query($query1);

	if( !$result1 )
	{
		die('Invalid query: ' . mysql_error());
	}

	$data1 = mysql_fetch_array($result1, MYSQL_ASSOC);

	while( count($data1) > 1 )
	{
		$query2=sprintf(
		"SELECT * FROM `horoscopes` WHERE ID = %s",
		mysql_real_escape_string($data1[horoscopeid])
		);

		$result2 = mysql_query($query2);

		if( !$result2 )
		{
			die('Invalid query: ' . mysql_error());
		}

		$data2 = mysql_fetch_array($result2, MYSQL_ASSOC);

		if( count($data2) > 1 )
		{
			if( $data2[deleted] == 0 )
			{
				$TotalVotes = 0;
				$TotalScore = 0;
				$AverageScore = 0;
				
				echo "<br/>ID: $data2[ID]<br/>";
				
				echo "Date: $data1[postdate]<br/>";
				
				echo "Score: ";
				
				echo "$RatingNames[1]: $data1[votes1], "; $TotalVotes += $data1[votes1]; $TotalScore += $data1[votes1] * $RatingValues[1];
				echo "$RatingNames[2]: $data1[votes2], "; $TotalVotes += $data1[votes2]; $TotalScore += $data1[votes2] * $RatingValues[2];
				echo "$RatingNames[3]: $data1[votes3], "; $TotalVotes += $data1[votes3]; $TotalScore += $data1[votes3] * $RatingValues[3];
				echo "$RatingNames[4]: $data1[votes4], "; $TotalVotes += $data1[votes4]; $TotalScore += $data1[votes4] * $RatingValues[4];
				echo "$RatingNames[5]: $data1[votes5], "; $TotalVotes += $data1[votes5]; $TotalScore += $data1[votes5] * $RatingValues[5];
				
				if( $TotalVotes > 0 )
				{
					$AverageScore = $TotalScore / $TotalVotes;
				}
				else
				{
					$AverageScore = 0;
				}
				
				echo "Average: $AverageScore<br/>";
				echo "<br/>Horoscope:<br/>$data2[text]";
				
				echo "<br/><br/><hr/>";
			}
		}

		$data1 = mysql_fetch_array($result1, MYSQL_ASSOC);
	}

	mysql_close($link);

?>
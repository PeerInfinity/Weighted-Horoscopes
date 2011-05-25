<?php

$postid = $_REQUEST['postid'];
$rating = $_REQUEST['rating'];

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
	die ('Can\'t use database : ' . mysql_error());
}

if( $rating >= 1 && $rating <= 5 )
{
	$query=sprintf(
	"SELECT COUNT(ID) FROM `instances` WHERE ID=%s",
	mysql_real_escape_string($postid)
	);
	
	$result = mysql_query($query);

	if( !$result )
	{
		die('Invalid query: ' . mysql_error());
	}
	
	$data = mysql_fetch_array($result, MYSQL_NUM);
	
	$PostCount = $data[0];
	
	if( $PostCount <= 0 )
	{
		echo "Invalid post";
	}
	else
	{
		$UserIP=$_SERVER['REMOTE_ADDR'];
		//echo "<br/>IP Address= $UserIP<br/>"; 	
		
		$query=sprintf(
		"SELECT COUNT(ID) FROM `votes` WHERE ipaddress='%s' AND instanceid='%s'",
		mysql_real_escape_string($UserIP),
		mysql_real_escape_string($postid)
		);

		$result = mysql_query($query);

		if( !$result )
		{
			die('Invalid query: ' . mysql_error());
		}

		$data = mysql_fetch_array($result, MYSQL_NUM);

		$VoteCount = $data[0];
		
		if( $VoteCount > 0 )
		{
			echo "You already voted on this post.<br/><br/>";

			$query=sprintf(
			"SELECT * FROM `votes` WHERE ipaddress='%s' AND instanceid='%s'",
			mysql_real_escape_string($UserIP),
			mysql_real_escape_string($postid)
			);

			$result = mysql_query($query);

			if( !$result )
			{
				die('Invalid query: ' . mysql_error());
			}

			$data = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$PreviousRating = $data[rating];

			echo "Your previous vote was \"$RatingNames[$PreviousRating]\".<br/><br/>";

			$query=sprintf(
			"UPDATE `instances` SET `%s` = `%s` - 1 WHERE `ID` = %s",
			mysql_real_escape_string($DatabaseFieldNames[$PreviousRating]),
			mysql_real_escape_string($DatabaseFieldNames[$PreviousRating]),
			mysql_real_escape_string($postid)
			);

			$result = mysql_query($query);

			if( !$result )
			{
				die('Invalid query: ' . mysql_error());
			}

			echo "Updating your vote...<br/><br/>";

			$query = sprintf(
			"UPDATE `votes` SET `rating` = '%s' WHERE ipaddress='%s' AND instanceid='%s'",
			mysql_real_escape_string($rating),
			mysql_real_escape_string($UserIP),
			mysql_real_escape_string($postid)
			);

			$result = mysql_query($query);

			if( !$result )
			{
				die('Invalid query: ' . mysql_error());
			}
		}
		else
		{
			$query = sprintf(
			"
			INSERT INTO `votes` (
			`ID` ,
			`ipaddress` ,
			`instanceid` ,
			`rating`
			)
			VALUES (
			NULL ,
			'%s' ,
			'%s' ,
			'%s'
			)
			",
			mysql_real_escape_string($UserIP),
			mysql_real_escape_string($postid),
			mysql_real_escape_string($rating)
			);

			$result = mysql_query($query);

			if( !$result )
			{
				die('Invalid query: ' . mysql_error());
			}
		}

		$query=sprintf(
		"UPDATE `instances` SET `%s` = `%s` + 1 WHERE `ID` = %s",
		mysql_real_escape_string($DatabaseFieldNames[$rating]),
		mysql_real_escape_string($DatabaseFieldNames[$rating]),
		mysql_real_escape_string($postid)
		);

		$result = mysql_query($query);

		if( !$result )
		{
			die('Invalid query: ' . mysql_error());
		}

		echo "Successfully submitted a rating of \"$RatingNames[$rating]\"";

		if( $RatingValues[$rating] < 0 )
		{
			echo ", $RatingValues[$rating] ";

			if( $RatingValues[$rating] == -1 )
			{
				echo "point.";
			}
			else
			{
				echo "points.";
			}
		}
		else
		{
			echo ", +$RatingValues[$rating] ";

			if( $RatingValues[$rating] == 1 )
			{
				echo "point.";
			}
			else
			{
				echo "points.";
			}
		}

		$query=sprintf(
		"SELECT * FROM `instances` WHERE ID = %s",
		mysql_real_escape_string($postid)
		);

		$result = mysql_query($query);

		if( !$result )
		{
			die('Invalid query: ' . mysql_error());
		}

		$data = mysql_fetch_array($result, MYSQL_ASSOC);

		if( count($data) > 1 )
		{
			$TotalVotes = 0;
			$TotalScore = 0;
			$AverageScore = 0;

			$TotalVotes += $data[votes1]; $TotalScore += $data[votes1] * $RatingValues[1];
			$TotalVotes += $data[votes2]; $TotalScore += $data[votes2] * $RatingValues[2];
			$TotalVotes += $data[votes3]; $TotalScore += $data[votes3] * $RatingValues[3];
			$TotalVotes += $data[votes4]; $TotalScore += $data[votes4] * $RatingValues[4];
			$TotalVotes += $data[votes5]; $TotalScore += $data[votes5] * $RatingValues[5];

			if( $TotalVotes > 0 )
			{
				$AverageScore = $TotalScore / $TotalVotes;
				
				$query=sprintf(
"UPDATE `horoscopes` SET
`totalvotes` = '%s',
`totalscore` = '%s',
`averagescore` = '%s'
WHERE `horoscopes`.`ID` =%s",
mysql_real_escape_string($TotalVotes),
mysql_real_escape_string($TotalScore),
mysql_real_escape_string($AverageScore),
mysql_real_escape_string($data[horoscopeid])
				);
				
				$result = mysql_query($query);

				if( !$result )
				{
					die('Invalid query: ' . mysql_error());
				}
			}
			else
			{
				$AverageScore = $DefaultScore;
			}

			echo "<br/><br/>Average rating is now $AverageScore";
			
		}
		else
		{
			echo "<br/><br/>Can't calculate total rating";
		}
	}
}
else
{
	echo "Invalid rating";
}

mysql_close($link);

?>
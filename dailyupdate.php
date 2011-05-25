<?php

include 'login.php';
include 'emailaddresses.php';
include 'constants.php';

class Horoscope
{
	public $m_ID = 0;
	
	public $m_text = "";
	
	public $m_isActive             = FALSE;
	public $m_isUsedRecently       = FALSE;
	public $m_isDownvotedBelowZero = FALSE;
	public $m_isDeleted            = FALSE;
	
	public $m_totalVotes   = 0;
	public $m_totalScore   = 0;
	public $m_averageScore = 0;

	public $m_instanceCount = 0;
	
	public $m_instances = array();
}

class HoroscopeInstance
{
	public $m_ID = 0;
	
	public $m_horoscopeID = 0;
	
	public $m_postDate = "";
	
	public $m_votes = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
	
	public $m_totalVotes = 0;
	public $m_totalScore = 0;
	public $m_averageScore = 0;
}

$ChoseHoroscope = FALSE;
$ChosenHoroscopeID = 0;
$ChosenHoroscopeText = "";

// first get the list of horoscopes

echo "Getting the list of horoscopes...<br/><br/>";

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

$query1="SELECT * FROM `horoscopes` WHERE 1";

$result1 = mysql_query($query1);

if( !$result1 )
{
	die('Invalid query: ' . mysql_error());
}

$data1 = mysql_fetch_array($result1, MYSQL_ASSOC);

$Horoscopes = array();

$curHoroscopeIndex = 1;

$TotalVotesForAllHoroscopes   = 0;
$TotalScoreForAllHoroscopes   = 0;
$AverageScoreForAllHoroscopes = 0;

$SumOfAverageScores = 0;

$TotalActivePosts = 0;
$TotalRecentlyUsedPosts = 0;

// go through the list of horoscopes

echo "reading the data from the database...<br/><br/>";

while( count($data1) > 1 )
{
	$Horoscopes[$curHoroscopeIndex] = new Horoscope();

	$Horoscopes[$curHoroscopeIndex]->m_ID        = $data1[ID];
	$Horoscopes[$curHoroscopeIndex]->m_text      = $data1[text];
	$Horoscopes[$curHoroscopeIndex]->m_isDeleted = $data1[deleted];

	$Horoscopes[$curHoroscopeIndex]->m_totalVotes = 0;
	$Horoscopes[$curHoroscopeIndex]->m_totalScore = 0;

	$Horoscopes[$curHoroscopeIndex]->m_isActive             = $data1[active];
	$Horoscopes[$curHoroscopeIndex]->m_isUsedRecently       = $data1[usedrecently];
	$Horoscopes[$curHoroscopeIndex]->m_isDownvotedBelowZero = $data1[downvotedbelowzero];
	$Horoscopes[$curHoroscopeIndex]->m_isDeleted            = $data1[deleted];

	$Horoscopes[$curHoroscopeIndex]->m_instanceCount = 0;

	$Horoscopes[$curHoroscopeIndex]->m_instances = array();

	$query2=sprintf(
	"SELECT * FROM `instances` WHERE horoscopeid = %s",
	mysql_real_escape_string($data1[ID])
	);

	$result2 = mysql_query($query2);

	if( !$result2 )
	{
		die('Invalid query: ' . mysql_error());
	}

	$data2 = mysql_fetch_array($result2, MYSQL_ASSOC);
	
	$curInstanceIndex = 1;

	while( count($data2) > 1 && !$ChoseHoroscope )
	{
		$Horoscopes[$curHoroscopeIndex]->m_instanceCount++;
	
		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex] = new HoroscopeInstance();

		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_ID = $data2[ID];

		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_horoscopeID = $data2[horoscopeid];

		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_postDate = $data2[postdate];

		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_votes = array
		(
			1 => $data2[votes1],
			2 => $data2[votes2],
			3 => $data2[votes3],
			4 => $data2[votes4],
			5 => $data2[votes5]
		);

		$TotalScore = 0;
		$TotalVotes = 0;
		$AverageScore = 0;

		$TotalVotes += $data2[votes1]; $TotalScore += $data2[votes1] * $RatingValues[1];
		$TotalVotes += $data2[votes2]; $TotalScore += $data2[votes2] * $RatingValues[2];
		$TotalVotes += $data2[votes3]; $TotalScore += $data2[votes3] * $RatingValues[3];
		$TotalVotes += $data2[votes4]; $TotalScore += $data2[votes4] * $RatingValues[4];
		$TotalVotes += $data2[votes5]; $TotalScore += $data2[votes5] * $RatingValues[5];

		if( $TotalVotes > 0 )
		{
			$AverageScore = $TotalScore / $TotalVotes;
		}
		else
		{
			$AverageScore = $DefaultScore;
		}

		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_totalVotes = $TotalVotes;
		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_totalScore = $TotalScore;
		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_averageScore = $AverageScore;

		$Horoscopes[$curHoroscopeIndex]->m_totalVotes += $TotalVotes;
		$Horoscopes[$curHoroscopeIndex]->m_totalScore += $TotalScore;

		$message = sprintf
		(
		"--processed instance %s, total votes: %s, total score: %s, average score: %s<br/>",
		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_ID,
		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_totalVotes,
		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_totalScore,
		$Horoscopes[$curHoroscopeIndex]->m_instances[$curInstanceIndex]->m_averageScore
		);
		echo $message;

		$data2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		
		$curInstanceIndex++;
	}
	
	if( $Horoscopes[$curHoroscopeIndex]->m_totalVotes > 0 )
	{
		$Horoscopes[$curHoroscopeIndex]->m_averageScore = $Horoscopes[$curHoroscopeIndex]->m_totalScore / $Horoscopes[$curHoroscopeIndex]->m_totalVotes;
	}
	else
	{
		$Horoscopes[$curHoroscopeIndex]->m_averageScore = $DefaultScore;
	}

	$TotalVotesForAllHoroscopes += $Horoscopes[$curHoroscopeIndex]->m_totalVotes;
	$TotalScoreForAllHoroscopes += $Horoscopes[$curHoroscopeIndex]->m_totalScore;

	if( $Horoscopes[$curHoroscopeIndex]->m_averageScore < 0 )
	{
		$Horoscopes[$curHoroscopeIndex]->m_isActive             = FALSE;
		$Horoscopes[$curHoroscopeIndex]->m_isDownvotedBelowZero = TRUE;
	}
	else
	{
		$Horoscopes[$curHoroscopeIndex]->m_isDownvotedBelowZero = FALSE;
	}
	
	if( $Horoscopes[$curHoroscopeIndex]->m_isActive )
	{
		$SumOfAverageScores += $Horoscopes[$curHoroscopeIndex]->m_averageScore;
	}

	if( $Horoscopes[$curHoroscopeIndex]->m_isActive )
	{
		$TotalActivePosts++;
	}

	if( $Horoscopes[$curHoroscopeIndex]->m_isUsedRecently )
	{
		$TotalRecentlyUsedPosts++;
	}
	
	$AverageScoreToUse = 0;
	
	if( $Horoscopes[$curHoroscopeIndex]->m_totalVotes > 0 )
	{
		$AverageScoreToUse = $Horoscopes[$curHoroscopeIndex]->m_averageScore;
	}

	$query=sprintf(
"UPDATE `horoscopes` SET
`active` = '%s',
`usedrecently` = '%s',
`downvotedbelowzero` = '%s',
`deleted` = '%s',
`totalvotes` = '%s',
`totalscore` = '%s',
`averagescore` = '%s',
`instancecount` = '%s'
WHERE `horoscopes`.`ID` =%s",
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_isActive),
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_isUsedRecently),
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_isDownvotedBelowZero),
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_isDeleted),
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_totalVotes),
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_totalScore),
mysql_real_escape_string($AverageScoreToUse),
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_instanceCount),
mysql_real_escape_string($Horoscopes[$curHoroscopeIndex]->m_ID)
	);
	
	//echo "query:<br/>$query<br/><br/>";
	
	$result = mysql_query($query);

	if( !$result )
	{
		die('Invalid query: ' . mysql_error());
	}
	
	$message = sprintf
	(
	"processed horoscope %s, instances: %s, total votes: %s, average score: %s, sum so far: %s<br/><br/>",
	$Horoscopes[$curHoroscopeIndex]->m_ID,
	$Horoscopes[$curHoroscopeIndex]->m_instanceCount,
	$Horoscopes[$curHoroscopeIndex]->m_totalVotes,
	$Horoscopes[$curHoroscopeIndex]->m_averageScore,
	$SumOfAverageScores
	);
	echo $message;

	$data1 = mysql_fetch_array($result1, MYSQL_ASSOC);
	
	$curHoroscopeIndex++;
}



// this isn't actually used anywhere
if( $TotalVotesForAllHoroscopes > 0 )
{
	$AverageScoreForAllHoroscopes = $TotalScoreForAllHoroscopes / $TotalVotesForAllHoroscopes;
}
else
{
	$AverageScoreForAllHoroscopes = $DefaultScore;
}

echo "finished reading the data from the database.<br/><br/>";

echo "sum: $SumOfAverageScores<br/><br/>";

foreach( $TumblrEmailAddresses as $EmailIndex => $curEmailAddress )
{
	echo "choosing a horoscope for $curEmailAddress...<br/><br/>";

	$RandomNumber = (float)rand() / (float)getrandmax() * (float)$SumOfAverageScores;

	echo "random number: $RandomNumber<br/><br/>";
	
	$SumForThisEmail = 0;
	
	$ChoseHoroscope = FALSE;

	foreach( $Horoscopes as $HoroscopeIndex => $curHoroscope )
	{
		if( !$ChoseHoroscope )
		{
			if( $curHoroscope->m_isActive )
			{
				$SumForThisEmail += $curHoroscope->m_averageScore;

				$message = sprintf
				(
				"adding horoscope %s, sum so far: %s<br/>",
				$curHoroscope->m_ID,
				$SumForThisEmail
				);
				echo $message;

				if( $SumForThisEmail >= $RandomNumber )
				{
					$ChoseHoroscope = TRUE;
					$ChosenHoroscopeID = $curHoroscope->m_ID;
					$ChosenHoroscopeText = $curHoroscope->m_text;

					$message = sprintf
					(
					"choosing horoscope %s<br/><br/>",
					$curHoroscope->m_ID
					);
					echo $message;
					
					$query=sprintf(
					"SELECT * FROM instances WHERE emailindex=%s ORDER BY ID DESC LIMIT 1",
					mysql_real_escape_string($EmailIndex)
					);
					
					$result = mysql_query($query);

					if( !$result )
					{
						die('Invalid query: ' . mysql_error());
					}

					$data = mysql_fetch_array($result, MYSQL_ASSOC);

					$PreviousHoroscopeExists = FALSE;
					
					if( count($data) > 1 )
					{
						$PreviousHoroscopeExists = TRUE;

						$PreviousHoroscopeInstanceID = $data[ID];
						$PreviousHoroscopeID = $data[horoscopeid];
						
						$query=sprintf(
						"SELECT * FROM `horoscopes` WHERE ID = %s",
						mysql_real_escape_string($PreviousHoroscopeID)
						);

						$result = mysql_query($query);

						if( !$result )
						{
							die('Invalid query: ' . mysql_error());
						}

						$data = mysql_fetch_array($result, MYSQL_ASSOC);
						
						$PreviousHoroscopeText = $data[text];
					}
					else
					{
						$PreviousHoroscopeExists = FALSE;
					}

					$TotalActivePosts--;
					$TotalRecentlyUsedPosts++;
					
					$query=sprintf(
					"
					INSERT INTO `instances` (
					`ID` ,
					`horoscopeid` ,
					`votes1` ,
					`votes2` ,
					`votes3` ,
					`votes4` ,
					`votes5` ,
					`emailindex`
					)
					VALUES (
					'NULL',
					'%s',
					'0',
					'0',
					'0',
					'0',
					'0',
					'%s'
					)
					",
					mysql_real_escape_string($ChosenHoroscopeID),
					mysql_real_escape_string($EmailIndex)
					);
					
					//echo "query:<br/>$query<br/><br/>";

					$result = mysql_query($query);

					if( !$result )
					{
						die('Invalid query: ' . mysql_error());
					}

					$query = "SELECT LAST_INSERT_ID()";

					$result = mysql_query($query);

					if( !$result )
					{
						die('Invalid query: ' . mysql_error());
					}

					$data = mysql_fetch_array($result, MYSQL_NUM);

					$NewPostID = $data[0];


					$query=sprintf(
"UPDATE `horoscopes` SET
`active` = '0',
`usedrecently` = '1',
`instancecount` = '%s'
WHERE `horoscopes`.`ID` =%s",
mysql_real_escape_string($curHoroscope->m_instanceCount + 1),
mysql_real_escape_string($curHoroscope->m_ID)
					);
					$result = mysql_query($query);

					if( !$result )
					{
						die('Invalid query: ' . mysql_error());
					}


					echo "Posting to Tumblr...<br/>";

					$curDate = date("l, F j, Y");
					
					$to = $curEmailAddress;
					$subject = "Horoscope for $curDate";
					$body = 
"!m
$ChosenHoroscopeText
";

					if( $PreviousHoroscopeExists )
					{
						$body .=
"
<br/>
<br/>
Yesterday's horoscope was:<br/><br/>
$PreviousHoroscopeText<br/>
<br/>
I found yesterday's horoscope:<br/><br/>
  [harmful]($RootURL/submitrating.php?postid=$PreviousHoroscopeInstanceID&rating=1)
| [not useful]($RootURL/submitrating.php?postid=$PreviousHoroscopeInstanceID&rating=2)
| [sort-of useful]($RootURL/submitrating.php?postid=$PreviousHoroscopeInstanceID&rating=3)
| [useful]($RootURL/submitrating.php?postid=$PreviousHoroscopeInstanceID&rating=4)
| [awesome]($RootURL/submitrating.php?postid=$PreviousHoroscopeInstanceID&rating=5)";
					}

					if( mail( $to, $subject, $body ) )
					{
						echo("<br/>Message successfully sent!<br/>");
					}
					else
					{
						echo("<br/>Message delivery failed...<br/>");
					}

					echo "<br/>To: $to";
					echo "<br/>Subject: $subject";
					echo ">br/>Body:<br.?$body";

					echo "<br/>";
					echo "<br/>";
					echo "Successfully completed daily update.<br/>";
					echo "<br/>";
					echo "Horoscope ID: $ChosenHoroscopeID<br/>";
					echo "Horoscope Text: <br/>$ChosenHoroscopeText<br/>";
				}
			}
			else
			{
				$message = sprintf
				(
				"skipping inactive horoscope %s<br/>",
				$curHoroscope->m_ID
				);
				echo $message;
			}
		}
	}

	if( !$ChoseHoroscope )
	{
		echo "Failed to choose a horoscope.<br/>";
	}
}

echo "Checking if we need to mark any recently used posts as active.<br/><br/>";

$query3="SELECT * FROM `instances` WHERE 1";

$result3 = mysql_query($query3);

if( !$result3 )
{
	die('Invalid query: ' . mysql_error());
}

$data3 = mysql_fetch_array($result3, MYSQL_ASSOC);

while( count($data3) > 1 && ( $TotalActivePosts < $TotalRecentlyUsedPosts || $TotalRecentlyUsedPosts > $MaxRecentlyUsedPosts*count($TumblrEmailAddresses) ) )
{
	if( $TotalActivePosts < $TotalRecentlyUsedPosts || $TotalRecentlyUsedPosts > $MaxRecentlyUsedPosts*count($TumblrEmailAddresses) )
	{
		$curHoroscopeID = $data3[horoscopeid];
		
		// search the array for the horoscope matching this ID
		// if it's found, and it's "recently used", then mark it as "active"
		foreach( $Horoscopes as $HoroscopeIndex => $curHoroscope )
		{
			if( $curHoroscope->m_ID == $curHoroscopeID )
			{
				if  ( 
						 $curHoroscope->m_isUsedRecently       &&
						!$curHoroscope->m_isActive             &&
						!$curHoroscope->m_isDownvotedBelowZero &&
						!$curHoroscope->m_isDeleted            
				    )
				{
					echo "marking horoscope $curHoroscopeID as active.";
				
					$query4=sprintf(
"UPDATE `horoscopes` SET
`active` = '1',
`usedrecently` = '0'
WHERE `horoscopes`.`ID` =%s",
mysql_real_escape_string($curHoroscopeID)
					);
					$result4 = mysql_query($query4);

					if( !$result4 )
					{
						die('Invalid query: ' . mysql_error());
					}
				
					$TotalActivePosts++;
					$TotalRecentlyUsedPosts--;
				}
			}
		}
	}
	
	$data3 = mysql_fetch_array($result3, MYSQL_ASSOC);
}

if( $TotalActivePosts < $TotalRecentlyUsedPosts || $TotalRecentlyUsedPosts > $MaxRecentlyUsedPosts*count($TumblrEmailAddresses) )
{
	echo "something went wrong trying to mark the horoscopes as active.";
}

echo "Done.<br/><br/>";

mysql_close($link);

?>
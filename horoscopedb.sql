-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: mysql.peerinfinity.com
-- Generation Time: May 22, 2011 at 01:37 AM
-- Server version: 5.1.56
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `horoscopedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `horoscopes`
--

CREATE TABLE IF NOT EXISTS `horoscopes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `usedrecently` tinyint(1) NOT NULL DEFAULT '0',
  `downvotedbelowzero` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `totalvotes` int(11) NOT NULL DEFAULT '0',
  `totalscore` int(11) NOT NULL DEFAULT '0',
  `averagescore` float NOT NULL DEFAULT '0',
  `instancecount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `horoscopes`
--

INSERT INTO `horoscopes` (`ID`, `text`, `active`, `usedrecently`, `downvotedbelowzero`, `deleted`, `totalvotes`, `totalscore`, `averagescore`, `instancecount`) VALUES
(1, 'Focus on [granularizing](http://lesswrong.com/lw/5p6/how_and_why_to_granularize/) your goals. When something you want to do seems impossible, try to break it down into a series of smaller steps.', 1, 0, 0, 0, 0, 0, 0, 0),
(2, 'Think about how your ideal self would handle the challenges you encounter. What would you do if you were smarter?', 1, 0, 0, 0, 0, 0, 0, 0),
(3, 'Take the time to evaluate your default responses to questions. They may not be as good as you think they are.', 1, 0, 0, 0, 0, 0, 0, 0),
(4, 'See if you can understand the functioning of an object well enough to create it from raw materials or scrap. Start with something simple, like paper fasteners.', 1, 0, 0, 0, 0, 0, 0, 0),
(5, 'What do you want? What are you doing to get it?', 1, 0, 0, 0, 0, 0, 0, 0),
(6, 'Your subconscious is trying to tell you something! Get a piece of paper and, for each of the previous three days, write down 3 things that happened as they leap into your mind. Put the paper down and come back to it several hours later and write down the theme that connects these nine events and what lesson you should learn from it.', 1, 0, 0, 0, 0, 0, 0, 0),
(7, 'Yesterday you did something for which you probably want to apologize. What was it? Can you do something today to make it right?', 1, 0, 0, 0, 0, 0, 0, 0),
(8, 'Today is a good day for starting new things. Choose something new that you''ve been putting off, and spend at least an hour working on it.', 1, 0, 0, 0, 0, 0, 0, 0),
(9, 'If you think about it carefully, the universe has recently shown you several examples of people working together successfully. What common principle can you see at work in these events?', 1, 0, 0, 0, 0, 0, 0, 0),
(10, 'Take some time to optimize the things you pay attention to for signal to noise ratio. Remove boring RSS feeds from your reader; find out how to stop receiving junk mail or getting calls from telemarketers; get rid of objects that clutter your living space or work space; perhaps even have a heart-to-heart with that friend you''ve been growing away from.', 1, 0, 0, 0, 0, 0, 0, 0),
(11, 'Today, indulge your curiosity. Find the answers to three interesting questions not directly related to your current projects. (Bonus points for finding answers to questions that can''t be answered via Google or Wikipedia, like the name of that cute person you see in the library every so often, or what''s sold in that odd little shop.)', 1, 0, 0, 0, 0, 0, 0, 0),
(12, 'That thing you''re hesitating over trying, the likes of which you''ve never done before, is probably easier than you think it is. You''re likely overestimating the difficulty because you lack any reference for it. Bear this in mind when reconsidering whether or not to try it.', 1, 0, 0, 0, 0, 0, 0, 0),
(13, 'Take the time today to put some important information in a more intuitive format. For example, you might make a pie chart or other visual representation of how you spent your money last month, or how you spent your time yesterday.', 0, 1, 0, 0, 0, 0, 0, 1),
(14, 'One of the things that you''re told today, which you''ve heard a thousand times before, will be false. Find it.', 1, 0, 0, 0, 0, 0, 0, 0),
(15, 'Question your questions. Are you asking the right things?', 1, 0, 0, 0, 0, 0, 0, 0),
(16, 'Keep an eye out for good ideas in unusual places today.', 1, 0, 0, 0, 0, 0, 0, 0),
(17, 'Today, spend time thinking about possible failure modes of your plans. What are the most likely things that could go wrong, and how will you handle it if they do?', 0, 1, 0, 0, 0, 0, 0, 1),
(18, 'Take some time to test your beliefs today. Devise and carry out at least one experiment.', 1, 0, 0, 0, 0, 0, 0, 0),
(19, 'Today, be paranoid. Don''t assume that the people around you are trustworthy, or sane, without evidence. (Evidence gained before today is, of course, admissible.)', 1, 0, 0, 0, 0, 0, 0, 0),
(20, 'Make a specific commitment today that you''re confident you can follow through on. Write it down and post it somewhere where you''ll see it regularly until it''s done.', 1, 0, 0, 0, 0, 0, 0, 0),
(21, 'The time for a lucid appraisal of your own abilities is prior to action, not in the middle of it. Once you find yourself engaged in real-time application of some skill or other, act as if your mastery of that skill isn''t at issue at all, rather than let yourself be distracted by assessments of the likelihood of failure, because they are likely to be self-fulfilling prophecies.', 1, 0, 0, 0, 0, 0, 0, 0),
(22, 'Resist the temptation to tell white lies or let convenient misunderstandings stand, today. Keep in mind that even if the truth is never discovered, you''ll still have to put in the effort of keeping up the appearance that the lie is true.', 1, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `instances`
--

CREATE TABLE IF NOT EXISTS `instances` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `horoscopeid` int(11) NOT NULL,
  `postdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `votes1` int(11) NOT NULL DEFAULT '0',
  `votes2` int(11) NOT NULL DEFAULT '0',
  `votes3` int(11) NOT NULL DEFAULT '0',
  `votes4` int(11) NOT NULL DEFAULT '0',
  `votes5` int(11) NOT NULL DEFAULT '0',
  `emailindex` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `instances`
--

INSERT INTO `instances` (`ID`, `horoscopeid`, `postdate`, `votes1`, `votes2`, `votes3`, `votes4`, `votes5`, `emailindex`) VALUES
(1, 13, '2011-05-22 01:28:25', 0, 0, 0, 0, 0, 0),
(2, 17, '2011-05-22 01:28:26', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ipaddress` text NOT NULL,
  `instanceid` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `votes`
--


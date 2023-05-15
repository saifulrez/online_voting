SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+07:00";

CREATE TABLE IF NOT EXISTS `ballot` (
  `ballot_id` int(10) ZEROFILL NOT NULL AUTO_INCREMENT,
  `election_id` int(5) ZEROFILL NOT NULL,
  `cdd_id` int(5) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `recovery_phrase` varchar(30),
  `recovery_phrase_activate` boolean DEFAULT false,
  PRIMARY KEY (`ballot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+07:00";

CREATE TABLE IF NOT EXISTS `election` (
  `election_id` int(5) ZEROFILL NOT NULL AUTO_INCREMENT,
  `title` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `description` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `start_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `end_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `announcement_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `hidden_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `img` varchar(255),
  PRIMARY KEY (`election_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `spa_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `table` varchar(80) NOT NULL,
  `rowid` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `added` datetime NOT NULL,
  `taken` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `spa_questions`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `spa_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;




CREATE TABLE `spa_questions_responses` (
  `id` int(10) UNSIGNED NOT NULL,
  `messageid` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `added` datetime NOT NULL,
  `taken` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `spa_questions_responses`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `spa_questions_responses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

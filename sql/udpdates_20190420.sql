SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `spa_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(80) NOT NULL,
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



CREATE TABLE `spa_questions_queue` (
  `id` int(10) UNSIGNED NOT NULL,
  `messageid` int(10) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `spa_questions_queue`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_questions_queue`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


ALTER TABLE `spa_sales` ADD `idclient` INT UNSIGNED NOT NULL AFTER `iduser`;
UPDATE spa_sales s
  LEFT JOIN spa_promos p ON p.id=s.idpromo
SET s.idclient=p.idclient

ALTER TABLE `spa_salestemp` ADD `idclient` INT UNSIGNED NOT NULL AFTER `iduser`;


ALTER TABLE `spa_notifications_queue` ADD `log` VARCHAR(1000) NOT NULL AFTER `body`;


CREATE TABLE `spa_notifications_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` VARCHAR(127) NOT NULL,
  `log` text NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `spa_notifications_log`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_notifications_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


  CREATE TABLE `spa_reservations` (
  `id` int(10) UNSIGNED NOT NULL,
  `promoid` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `book_date` datetime NOT NULL,
  `comments` varchar(1000) DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `spa_reservations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_reservations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

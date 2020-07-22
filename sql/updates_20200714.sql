CREATE TABLE `spa_client_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientid` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `spa_client_views`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `spa_client_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `spa_glossary_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `glossaryid` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `spa_glossary_views`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `spa_glossary_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;




ALTER TABLE `spa_client_views` ADD FOREIGN KEY (`clientid`) REFERENCES `spa_clients`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `spa_client_views` ADD CONSTRAINT `spa_client_views_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `spa_users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;


ALTER TABLE `spa_glossary` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `spa_glossary_views` ADD FOREIGN KEY (`glossaryid`) REFERENCES `spa_glossary`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `spa_glossary_views` ADD CONSTRAINT `spa_glossary_views_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `spa_users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

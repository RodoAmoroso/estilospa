

CREATE TABLE `spa_promos_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(127) NOT NULL,
  `caption` text NOT NULL,
  `image` varchar(500) NOT NULL,
  `added` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `visible` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `spa_promos_categories`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `spa_promos_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `spa_promos` ADD `categoryid` INT UNSIGNED NULL DEFAULT NULL AFTER `idpromotype`;
ALTER TABLE `spa_promos`
  ADD CONSTRAINT `spa_promos_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `spa_promos_categories` (`id`) ON DELETE SET NULL;
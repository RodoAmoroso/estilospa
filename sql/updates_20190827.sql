RENAME TABLE `estilospa`.`spa_salestemp` TO `estilospa`.`spa_sales_temp`;
RENAME TABLE `estilospa`.`spa_salesstatus` TO `estilospa`.`spa_sales_status`;


CREATE TABLE `spa_sales_gift` (
  `id` int(10) UNSIGNED NOT NULL,
  `from_user` int(10) UNSIGNED NULL DEFAULT NULL,
  `to_user` int(10) UNSIGNED NULL DEFAULT NULL,
  `message` varchar(255) NOT NULL,
  `promoid` int(10) UNSIGNED NOT NULL,
  `hash` char(64) NOT NULL,
  `added` datetime NOT NULL,
  `downloads` int(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `added`
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `spa_sales_gift`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_sales_gift`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


--- volcar gift to sales_gift




CREATE TABLE `spa_sales_reservations` (
  `id` int(10) UNSIGNED NOT NULL,
  `saleid` int(10) UNSIGNED NOT NULL,
  `reservationid` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `spa_sales_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saleid` (`saleid`),
  ADD KEY `spa_sales_reservations_ibfk_2` (`reservationid`);

ALTER TABLE `spa_sales_reservations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `spa_sales_reservations`
  ADD CONSTRAINT `spa_sales_reservations_ibfk_1` FOREIGN KEY (`saleid`) REFERENCES `spa_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spa_sales_reservations_ibfk_2` FOREIGN KEY (`reservationid`) REFERENCES `spa_reservations` (`id`) ON DELETE CASCADE;


ALTER TABLE `spa_promos` 
  ADD `gift` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `sale`;




ALTER TABLE `spa_sales` 
  ADD `notified` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `status`;
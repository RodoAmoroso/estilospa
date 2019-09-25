RENAME TABLE `estilospa`.`spa_salestemp` TO `estilospa`.`spa_sales_temp`;
RENAME TABLE `estilospa`.`spa_salesstatus` TO `estilospa`.`spa_sales_status`;


ALTER TABLE `spa_sales_temp` 
  ADD `reservationid` INT UNSIGNED NULL DEFAULT NULL AFTER `idcode`;

  

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




CREATE TABLE `spa_reservations_sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `saleid` int(10) UNSIGNED NOT NULL,
  `reservationid` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `spa_reservations_sales`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_reservations_sales`
  ADD KEY `saleid` (`saleid`),
  ADD KEY `spa_reservations_sales_ibfk_2` (`reservationid`);

ALTER TABLE `spa_reservations_sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `spa_reservations_sales`
  ADD CONSTRAINT `spa_reservations_sales_ibfk_1` FOREIGN KEY (`saleid`) REFERENCES `spa_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spa_reservations_sales_ibfk_2` FOREIGN KEY (`reservationid`) REFERENCES `spa_reservations` (`id`) ON DELETE CASCADE;


ALTER TABLE `spa_promos` 
  ADD `gift` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `sale`;
UPDATE `spa_promos` SET gift=sale
UPDATE `spa_promos` SET sale=1




ALTER TABLE `spa_sales` 
  ADD `notified` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `status`;


ALTER TABLE `spa_reservations` 
  CHANGE `promoid` `promoid` INT(10) UNSIGNED NULL DEFAULT NULL,
  CHANGE `status` `status` SMALLINT UNSIGNED NOT NULL;


ALTER TABLE `spa_reservations` 
  ADD CONSTRAINT `spa_reservations_ibfk_1` FOREIGN KEY (`clientid`) REFERENCES `spa_clients`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;


ALTER TABLE `spa_reservations` 
  ADD CONSTRAINT `spa_reservations_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `spa_users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;


ALTER TABLE `spa_reservations` 
  ADD CONSTRAINT `spa_reservations_ibfk_3` FOREIGN KEY (`promoid`) REFERENCES `spa_promos`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;


ALTER TABLE `spa_reservations` 
  ADD CONSTRAINT `spa_reservations_ibfk_4` FOREIGN KEY (`status`) REFERENCES `spa_reservations_status`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;




CREATE TABLE `spa_holidays` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(127) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `spa_holidays`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;





CREATE TABLE `spa_sales_vouchers` (
  `id` int(10) UNSIGNED NOT NULL,
  `saleid` int(10) UNSIGNED NOT NULL,
  `downloads` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `gift` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `to_user` varchar(80) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `spa_sales_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saleid` (`saleid`);
ALTER TABLE `spa_sales_vouchers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `spa_sales_vouchers`
  ADD CONSTRAINT `spa_sales_vouchers_ibfk_1` FOREIGN KEY (`saleid`) REFERENCES `spa_sales` (`id`) ON DELETE CASCADE;
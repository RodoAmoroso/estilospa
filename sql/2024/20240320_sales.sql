ALTER TABLE `spa_sales_temp` ADD FOREIGN KEY (`iduser`) REFERENCES `spa_users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `spa_sales_temp` ADD FOREIGN KEY (`idpromo`) REFERENCES `spa_promos`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `spa_sales_temp` ADD FOREIGN KEY (`idclient`) REFERENCES `spa_clients`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `spa_sales_temp` CHANGE `idpromo` `idpromo` INT(10) UNSIGNED NULL DEFAULT NULL;



ALTER TABLE `spa_sales_temp` CHANGE `idcode` `idcode` INT(10) UNSIGNED NULL DEFAULT NULL;
UPDATE spa_sales_temp SET idcode=NULL WHERE idcode=0;

ALTER TABLE `spa_sales_temp` ADD FOREIGN KEY (`idcode`) REFERENCES `spa_vouchers_codes`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE `spa_sales_temp` ADD `modified` DATETIME NULL DEFAULT NULL AFTER `added`;

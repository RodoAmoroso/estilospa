ALTER TABLE `spa_sales` CHANGE `iduser` `iduser` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `spa_sales` ADD FOREIGN KEY (`iduser`) REFERENCES `spa_users`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT; ALTER TABLE `spa_sales` ADD FOREIGN KEY (`idclient`) REFERENCES `spa_clients`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE `spa_sales` CHANGE `idpromo` `idpromo` INT(10) UNSIGNED NULL DEFAULT NULL;


UPDATE spa_sales SET idpromo=NULL WHERE NOT EXISTS (SELECT 1 FROM spa_promos WHERE spa_promos.id = spa_sales.idpromo);

ALTER TABLE `spa_sales` ADD FOREIGN KEY (`idpromo`) REFERENCES `spa_promos`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT;


ALTER TABLE `spa_sales` CHANGE `status` `status` SMALLINT(5) UNSIGNED NULL DEFAULT '1';
ALTER TABLE `spa_sales` ADD FOREIGN KEY (`status`) REFERENCES `spa_sales_status`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT;



ALTER TABLE `spa_sales` CHANGE `collection_status` `payment_status` VARCHAR(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;



SELECT iduser, COUNT(*) as total
FROM `spa_sales`
WHERE payment_status='approved'
GROUP BY iduser
HAVING total > 1
ORDER BY total DESC;
ALTER TABLE `spa_users` CHANGE `social` `social` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '';
ALTER TABLE `spa_users` CHANGE `blocked` `blocked` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0', CHANGE `deleted` `deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';

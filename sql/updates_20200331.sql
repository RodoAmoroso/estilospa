ALTER TABLE `spa_promos`
	CHANGE `views` `views` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	CHANGE `deleted` `deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	CHANGE `position` `position` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	CHANGE `position_client` `position_client` INT(10) UNSIGNED NOT NULL DEFAULT '0';

INSERT INTO `spa_usertypes` (`type`, `name`, `privileges`) VALUES ('franchise', 'Franquicia', '');
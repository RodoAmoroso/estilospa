ALTER TABLE `spa_questions_responses` ADD `approved` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER `taken`;
ALTER TABLE `spa_questions_responses` ADD `modified` DATETIME NULL DEFAULT NULL AFTER `approved`;

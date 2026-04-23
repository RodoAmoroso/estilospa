CREATE TABLE `spa_errors_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `log` text NOT NULL,
  `params` text DEFAULT NULL,
  `query` text DEFAULT NULL,
  `userdata` text DEFAULT NULL,
  `added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
ALTER TABLE `spa_errors_log`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_errors_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;



CREATE TABLE `spa_main_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
	`reference` varchar(20) NOT NULL,
  `visible` tinyint(1) UNSIGNED NOT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `added` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `spa_main_categories` (`id`, `name`, `reference`, `visible`, `position`, `added`, `modified`) VALUES
(1, '<span>para</span> <span class=\"ff-birthstone\">Mi</span>', 'para-mi', 1, 1, '2025-05-12 13:26:01', NULL),
(2, '<span>para</span> <span class=\"ff-birthstone\">Compartir</span>', 'para-compartir', 1, 2, '2025-05-12 13:26:01', NULL),
(3, '<span>para</span> <span class=\"ff-birthstone\">Regalar</span>', 'para-regalar', 1, 3, '2025-05-12 13:26:28', NULL);
ALTER TABLE `spa_main_categories`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_main_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `spa_promos_categories` ADD `main_category_id` INT UNSIGNED NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `spa_promos_categories` ADD FOREIGN KEY (`main_category_id`) REFERENCES `spa_main_categories`(`id`) ON DELETE RESTRICT ON UPDATE SET NULL;



CREATE TABLE `spa_promos_main_categories_relations` (
  `id` int(10) UNSIGNED NOT NULL,
  `promo_id` int(10) UNSIGNED NOT NULL,
  `main_category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
ALTER TABLE `spa_promos_main_categories_relations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo_id` (`promo_id`),
  ADD KEY `main_category_id` (`main_category_id`);
ALTER TABLE `spa_promos_main_categories_relations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `spa_promos_main_categories_relations`
  ADD CONSTRAINT `spa_promos_main_categories_relations_ibfk_1` FOREIGN KEY (`promo_id`) REFERENCES `spa_promos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spa_promos_main_categories_relations_ibfk_2` FOREIGN KEY (`main_category_id`) REFERENCES `spa_main_categories` (`id`) ON DELETE CASCADE;
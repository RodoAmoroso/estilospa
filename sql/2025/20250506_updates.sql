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
  `visible` tinyint(1) UNSIGNED NOT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `added` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `spa_main_categories` (`id`, `name`, `visible`, `position`, `added`, `modified`) VALUES
(1, '<span>para</span> <span class=\"ff-birthstone\">Mi</span>', 1, 1, '2025-05-12 13:26:01', NULL),
(2, '<span>para</span> <span class=\"ff-birthstone\">Compartir</span>', 1, 2, '2025-05-12 13:26:01', NULL),
(3, '<span>para</span> <span class=\"ff-birthstone\">Regalar</span>', 1, 3, '2025-05-12 13:26:28', NULL);
ALTER TABLE `spa_main_categories`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `spa_main_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `spa_promos_categories` ADD `main_category_id` INT UNSIGNED NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `spa_promos_categories` ADD FOREIGN KEY (`main_category_id`) REFERENCES `spa_main_categories`(`id`) ON DELETE RESTRICT ON UPDATE SET NULL;

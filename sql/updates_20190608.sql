CREATE TABLE `spa_reservations_status` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `label` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `spa_reservations_status` (`id`, `name`, `label`) VALUES
(0, 'A confirmar', 'yellow-3'),
(1, 'Confirmada', 'green-3'),
(2, 'Esperando confirmación del usuario', 'aqua-3'),
(3, 'Excluída', 'pink-2'),
(4, 'Cancelada por el centro', 'pink-2'),
(5, 'Cancelada por el usuario', 'pink-3');


ALTER TABLE `spa_reservations_status`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `spa_reservations_status`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- Create junction table for units and peripherals (many-to-many relationship)
CREATE TABLE IF NOT EXISTS `unit_peripherals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) NOT NULL,
  `peripheral_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_unit_peripheral` (`unit_id`, `peripheral_id`),
  KEY `unit_id` (`unit_id`),
  KEY `peripheral_id` (`peripheral_id`),
  CONSTRAINT `fk_unit_peripherals_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_unit_peripherals_peripheral` FOREIGN KEY (`peripheral_id`) REFERENCES `peripherals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

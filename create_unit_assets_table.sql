-- Create junction table for units and assets (many-to-many relationship)
CREATE TABLE IF NOT EXISTS `unit_assets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) NOT NULL,
  `asset_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_unit_asset` (`unit_id`, `asset_id`),
  KEY `unit_id` (`unit_id`),
  KEY `asset_id` (`asset_id`),
  CONSTRAINT `fk_unit_assets_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_unit_assets_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

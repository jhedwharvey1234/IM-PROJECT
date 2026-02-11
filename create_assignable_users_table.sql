-- Create assignable_users table if it doesn't exist
CREATE TABLE IF NOT EXISTS `assignable_users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

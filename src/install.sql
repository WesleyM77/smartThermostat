USE `smart_thermostat`;

-- Dumping structure for table smart_thermostat.config
CREATE TABLE IF NOT EXISTS `config` (
    `name` char(255) NOT NULL,
    `value` varchar(1024) NOT NULL DEFAULT '0',
    `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping structure for table smart_thermostat.zone
CREATE TABLE IF NOT EXISTS `zone` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` char(255) NOT NULL,
    `ac_pin` int(2) unsigned DEFAULT NULL,
    `heat_pin` int(2) unsigned DEFAULT NULL,
    `fan_pin` int(2) unsigned DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping structure for table smart_thermostat.schedule
CREATE TABLE IF NOT EXISTS `schedule` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` char(255) NOT NULL,
    `zone_id` int(10) unsigned NOT NULL,
    `active` int(1) unsigned NOT NULL DEFAULT 1,
    `deleted` int(1) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `zone_id_fk` (`zone_id`),
    CONSTRAINT `zone_id_fk` FOREIGN KEY (`zone_id`) REFERENCES `zone` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping structure for table smart_thermostat.schedule_details
CREATE TABLE IF NOT EXISTS `schedule_details` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `schedule_id` int(255) unsigned NOT NULL,
    `day_of_week` int(1) unsigned NOT NULL COMMENT 'Constant controlled.',
    `start_time` decimal(2,2) unsigned NOT NULL COMMENT '0-23. 0.25 increments',
    `end_time` decimal(2,2) unsigned NOT NULL COMMENT '0-23. 0.25 increments',
    `temp_goal` int(255) unsigned NOT NULL,
    `scale` int(255) unsigned NOT NULL COMMENT 'Fahrenheit or Celcius. Constant controlled.',
    `mode` int(255) unsigned NOT NULL COMMENT 'Constant controlled.',
    PRIMARY KEY (`id`),
    KEY `schedule_id` (`schedule_id`),
    CONSTRAINT `schedule_id_fk` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

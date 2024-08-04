
-- Added on 06th Jan 2023 braham // naveen kumar

UPDATE `modules` SET `name` = 'banner_images', `display_name` = 'Manage Banner Images', `description` = 'Manage Banner Images' WHERE `modules`.`id` = 45;

DELETE FROM `modules` WHERE `modules`.`id` = 41;
INSERT INTO `modules` (`id`, `parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES (NULL, '0', 'forms', 'Manage Form', 'Manage Form', NULL, 'view,add,edit,delete', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `modules` (`id`, `parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES (NULL, '0', 'downloads', 'Manage Footer Menu / Other', 'Manage Footer Menu / Other', NULL, 'view,add,edit,delete', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `modules` (`id`, `parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES (NULL, '0', 'roles', 'Manage Roles', 'Manage Roles', NULL, 'view,add,edit,delete', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

ALTER TABLE `admins` ADD CONSTRAINT `roles_id` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- Added on 10th Jan 2023 (Abhishek)
ALTER TABLE `faqs` ADD `destination_id` INT  NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `faqs` ADD `sub_destination_id` INT NULL DEFAULT NULL AFTER `destination_id`;

ALTER TABLE `faqs` ADD FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `faqs` ADD FOREIGN KEY (`sub_destination_id`) REFERENCES `destinations`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
-----Update(Pradeepk) (12-01-23) coloumn in order table------------
ALTER TABLE `orders` CHANGE `discount` `discount` DECIMAL(10,2) NULL DEFAULT NULL, CHANGE `discount_type` `discount_type` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `sub_total_amount` `sub_total_amount` DECIMAL(10,2) NULL DEFAULT NULL, CHANGE `total_amount` `total_amount` DECIMAL(10,2) NULL DEFAULT NULL;

ALTER TABLE `orders` CHANGE `discount` `discount` DECIMAL(10,2) NULL DEFAULT '0', CHANGE `sub_total_amount` `sub_total_amount` DECIMAL(10,2) NULL DEFAULT '0', CHANGE `total_amount` `total_amount` DECIMAL(10,2) NULL DEFAULT '0';

ALTER TABLE `orders` CHANGE `order_no` `order_no` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

-- Added on 12th Jan 2023 (Braham)
-- 1. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_master_group` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `enquiries_master_group`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_master_group`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_master` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL,
  `group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_name` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `enquiries_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);
ALTER TABLE `enquiries_master`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `enquiries_master` ADD FOREIGN KEY (`group_id`) REFERENCES `enquiries_master_group`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;
-- -----------------------------------

-- Added on 13th Jan 2023 (Braham)
-- 1. -----------------------------------
 RENAME TABLE `enquiries` TO `enquiries_17jan2023`;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `enquiry_no_id` varchar(100) DEFAULT NULL,
  `cc_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `country_code` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `content` text,
  `destination` int(11) DEFAULT NULL,
  `sub_destination` int(11) DEFAULT NULL,
  `package` int(11) DEFAULT NULL,
  `accommodation` int(11) DEFAULT NULL,
  `activity` int(11) DEFAULT NULL,
  `lead_source` int(11) DEFAULT NULL,
  `lead_status` int(11) DEFAULT NULL,
  `lead_sub_status` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `followup_date` datetime DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_date` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `is_deleted` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_interactions` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `cc_id` int(11) DEFAULT NULL,
  `destination` int(11) DEFAULT NULL,
  `sub_destination` int(11) DEFAULT NULL,
  `package` int(11) DEFAULT NULL,
  `accommodation` int(11) DEFAULT NULL,
  `activity` int(11) DEFAULT NULL,
  `lead_status` int(11) DEFAULT NULL,
  `lead_sub_status` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `followup_date` datetime DEFAULT NULL,
  `comment` text,
  `created_by` int(11) DEFAULT NULL,
  `created_type` enum('backend','customer','','') DEFAULT 'customer',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `enquiries_interactions`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
ALTER TABLE `enquiries_interactions` ADD FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
-- 3. -----------------------------------
ALTER TABLE `users` CHANGE `dob` `dob` DATE NULL;
-- -----------------------------------

-- Added on 16th Jan 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `enquiries_interactions` ADD `lead_source` INT NULL AFTER `activity`;
-- -----------------------------------

-- Added on 13th Jan 2023 (Abhishek)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admin_user_activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(120) DEFAULT NULL,
  `module` varchar(60) NOT NULL,
  `module_id` int(10) NOT NULL,
  `module_desc` varchar(255) NOT NULL,
  `sub_module` text,
  `sub_module_id` int(11) DEFAULT NULL,
  `sub_module_desc` varchar(255) DEFAULT NULL,
  `sub_sub_module` text,
  `sub_sub_module_id` int(11) DEFAULT NULL,
  `sub_sub_module_desc` varchar(255) DEFAULT NULL,
  `data_after_action` text,
  `action` varchar(120) NOT NULL,
  `user_agent` text NOT NULL,
  `ip_address` varchar(39) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `admin_user_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `sub_module_id` (`sub_module_id`),
  ADD KEY `sub_sub_module_id` (`sub_sub_module_id`);

ALTER TABLE `admin_user_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-----Update(Pradeepk) (16-01-23) coloumn in packages table------------
ALTER TABLE `packages` ADD `inclusions_chk` TINYINT(1) NULL DEFAULT NULL AFTER `terms_conditions`, ADD `exclusions_chk` TINYINT(1) NULL DEFAULT NULL AFTER `inclusions_chk`, ADD `payment_policy_chk` TINYINT(1) NULL DEFAULT NULL AFTER `exclusions_chk`, ADD `cancellation_policy_chk` TINYINT(1) NULL DEFAULT NULL AFTER `payment_policy_chk`, ADD `terms_conditions_chk` TINYINT(1) NULL DEFAULT NULL AFTER `cancellation_policy_chk`;

-- Added on 18th Jan 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `form_elements` CHANGE `form_id` `form_id` INT(11) NULL;
ALTER TABLE `enquiries` ADD `form_data` TEXT NULL AFTER `owner_date`;
-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_for_types` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) DEFAULT NULL,
  `enquiry_for_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `enquiries_for_types`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_for_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- 3. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_interactions_accommodations` (
  `id` int(11) NOT NULL,
  `enquiry_interaction_id` int(11) DEFAULT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `enquiries_interactions_accommodations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_interactions_accommodations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- 4. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_interactions_destinations` (
  `id` int(11) NOT NULL,
  `enquiry_interaction_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `type` enum('destination','sub_destination','','') DEFAULT 'destination',
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `enquiries_interactions_destinations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_interactions_destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- 5. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_interactions_for_types` (
  `id` int(11) NOT NULL,
  `enquiry_interaction_id` int(11) DEFAULT NULL,
  `enquiry_for_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `enquiries_interactions_for_types`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_interactions_for_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- 6. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_accommodations` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) DEFAULT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `enquiries_accommodations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_accommodations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- 7. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `enquiries_destinations` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `type` enum('destination','sub_destination','','') DEFAULT 'destination',
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `enquiries_destinations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `enquiries_destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-----------------------------------------
----------Updated By pardeepk 19-12-2023---------
ALTER TABLE `image_categories` ADD `modules` VARCHAR(255) NULL DEFAULT NULL AFTER `parent_id`;

-- Added on 19th Jan 2023 (Braham)
ALTER TABLE `enquiries` ADD FOREIGN KEY (`form_id`) REFERENCES `forms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `form_elements` ADD FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `enquiries` ADD UNIQUE(`enquiry_no_id`);


-- Added on 20th Jan 2023 (Braham)
-- 1. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `destination_locations` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `destination_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);
ALTER TABLE `destination_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `destination_locations`
  ADD CONSTRAINT `destination_locations_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON UPDATE CASCADE;
COMMIT;

-- Added on 24th Jan 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `itenaries` ADD `location_id` INT NULL AFTER `package_id`;
ALTER TABLE `package_price_info` CHANGE `discount_type` `discount_type` TINYINT(1) NULL COMMENT '(1 => Flat, 2 => Percent)';
ALTER TABLE `package_price_info` CHANGE `discount` `discount` DECIMAL(10,2) NULL;
ALTER TABLE `package_price_info` CHANGE `sub_total_amount` `sub_total_amount` DECIMAL(15,2) NULL;
ALTER TABLE `package_price_info` CHANGE `final_amount` `final_amount` DECIMAL(15,2) NULL;

-- Added on 25th Jan 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `package_accommodations` DROP FOREIGN KEY `package_price_id`; ALTER TABLE `package_accommodations` ADD CONSTRAINT `package_price_id` FOREIGN KEY (`package_price_id`) REFERENCES `package_price_info`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `accommodation_locations` (
  `id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `destination_location_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `accommodation_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodation_id` (`accommodation_id`),
  ADD KEY `destination_location_id` (`destination_location_id`);
ALTER TABLE `accommodation_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `accommodation_locations`
  ADD CONSTRAINT `accommodation_locations_ibfk_1` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `accommodation_locations_ibfk_2` FOREIGN KEY (`destination_location_id`) REFERENCES `destination_locations` (`id`) ON UPDATE CASCADE;
COMMIT;

-- Added on 27th Jan 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders` ADD `package_type` INT NULL AFTER `package_id`;
ALTER TABLE `orders` CHANGE `method` `method` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL COMMENT '1=>Paypal,2=>Bank Transfer';
ALTER TABLE `orders` CHANGE `service_level` `service_level` INT(11) NULL;
ALTER TABLE `orders` CHANGE `order_no` `order_no` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

-- Added on 30th Jan 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders` ADD `trip_date` datetime DEFAULT NULL AFTER `total_amount`, ADD `price_category_choice_record` TEXT NULL AFTER `trip_date`;
ALTER TABLE `enquiries` ADD `order_id` INT NULL AFTER `enquiry_no_id`;
ALTER TABLE `orders` ADD `address1` VARCHAR(100) NULL AFTER `country`, ADD `address2` VARCHAR(100) NULL AFTER `address1`, ADD `city` VARCHAR(100) NULL AFTER `address2`, ADD `state` VARCHAR(100) NULL AFTER `city`, ADD `zip_code` VARCHAR(100) NULL AFTER `state`;

-- Added on 01st Feb 2023 (Braham)
-- 1. -----------------------------------
UPDATE `form_attributes` SET `type` = 'name' WHERE `form_attributes`.`id` = 7;
UPDATE `form_elements` SET `type` = 'name' WHERE `form_elements`.`id` = 1;
INSERT INTO `form_attributes` (`id`, `label`, `type`, `validations`, `status`, `created_at`) VALUES (NULL, 'Country', 'country', '', '1', '2023-02-01 00:00:00'), (NULL, 'Zip Code', 'zipcode', '', '1', '2023-02-01 00:00:00');
-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `package_settings` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `hide` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `package_settings` (`id`, `slug`, `name`, `hide`, `status`, `created_at`, `updated_at`) VALUES
(1, 'hide_book_now', 'Hide Online Booking Option', 1, 1, '2023-02-01 18:07:08', '2023-02-01 19:14:31'),
(2, 'hide_price', 'Hide Package Price', 0, 1, '2023-02-01 18:10:16', '2023-02-01 18:10:16'),
(3, 'hide_duration', 'Hide Package Duration', 0, 1, '2023-02-01 18:10:25', '2023-02-01 18:10:25'),
(4, 'hide_accomodation', 'Hide Package Accommodation', 0, 1, '2023-02-01 18:10:32', '2023-02-01 18:10:32'),
(5, 'display_accommodation_type', 'Hide Package Accommodation Category', 0, 1, '2023-02-01 18:10:41', '2023-02-01 18:10:41'),
(6, 'hide_banner', 'Hide Banner', 0, 1, '2023-02-01 18:10:50', '2023-02-01 18:10:50'),
(7, 'hide_search_form', 'Hide Search Form', 0, 1, '2023-02-01 18:10:56', '2023-02-01 18:10:56'),
(9, 'hide_destination_from_itinerary', 'Hide Destination / City from itinerary', 0, 1, '2023-02-01 18:16:31', '2023-02-01 19:08:44');
ALTER TABLE `package_settings`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `package_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;
-- 3. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `package_to_settings` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `setting_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `package_to_settings`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `package_to_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
ALTER TABLE `package_to_settings` ADD FOREIGN KEY (`setting_id`) REFERENCES `package_settings`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `package_to_settings` ADD FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


-- Added on 01st Feb 2023 (Pradeepk)
ALTER TABLE `blog_categories` ADD `content` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `status`;

-- Added on 02 Feb 2023 (Braham)
-- 1. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `package_departures` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `package_departures`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `package_departures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `package_departure_capacities` (
  `id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `package_departure_id` int(11) DEFAULT NULL,
  `package_price_id` int(11) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `booking` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `package_departure_capacities`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `package_departure_capacities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- Added on 02 Feb 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `paskage_airlines` ADD `airline_code` VARCHAR(10) NULL AFTER `airline_name`;
RENAME TABLE `paskage_airlines` TO `package_airlines`;
ALTER TABLE `package_airlines` DROP `airline_from`, DROP `airline_to`, DROP `price`;
-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `package_flights` (
  `id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `flight_number` varchar(100) DEFAULT NULL,
  `flight_time` varchar(100) DEFAULT NULL,
  `airline_name` varchar(100) DEFAULT NULL,
  `flight_departure` varchar(100) DEFAULT NULL,
  `flight_arrival` varchar(100) DEFAULT NULL,
  `flight_comment` text,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `package_flights`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `package_flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- 3. -----------------------------------
ALTER TABLE `common_images` ADD `is_default` INT NULL DEFAULT '0' AFTER `sort_order`;

-- Added on 06 Feb 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `packages` ADD `is_activity` INT NULL DEFAULT '0' AFTER `tour_type`;
ALTER TABLE `packages` ADD `activity_duration` INT NULL DEFAULT '0' AFTER `package_duration_days`, ADD `activity_duration_type` INT NULL DEFAULT '1' COMMENT '1=Hours 2=Days' AFTER `activity_duration`;

-- Added on 06 Feb 2023 (Pradeepk)
ALTER TABLE `team_management` ADD `featured` TINYINT(1) NOT NULL AFTER `status`;

ALTER TABLE `seo_meta_tags`  ADD `page_title` VARCHAR(255) NULL DEFAULT NULL  AFTER `id`,  ADD `page_brief` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL  AFTER `page_title`,  ADD `page_description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL  AFTER `page_brief`,  ADD `page_url` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL  AFTER `page_description`;
ALTER TABLE `seo_meta_tags`  ADD `pages_detail_url` VARCHAR(255) NULL DEFAULT NULL  AFTER `page_url`;
ALTER TABLE `seo_meta_tags` CHANGE `pages_detail_url` `page_detail_url` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `seo_meta_tags`  ADD `image` VARCHAR(255) NULL DEFAULT NULL  AFTER `other_meta_tag`;

UPDATE `seo_meta_tags` SET `page_title` = 'test', `page_brief` = 'test1', `page_description` = 'test2', `page_url` = 'test1', `pages_detail_url` = 'test1' WHERE `seo_meta_tags`.`id` = 1; UPDATE `seo_meta_tags` SET `page_title` = 'test1', `page_brief` = 'test1', `page_description` = 'test1', `page_url` = 'test1', `pages_detail_url` = 'test1' WHERE `seo_meta_tags`.`id` = 2; UPDATE `seo_meta_tags` SET `page_title` = 'test1', `page_brief` = 'test1', `page_description` = 'test1', `page_url` = 'test1', `pages_detail_url` = 'test1' WHERE `seo_meta_tags`.`id` = 3; UPDATE `seo_meta_tags` SET `page_title` = 'test1', `page_brief` = 'test1', `page_description` = 'test1', `page_url` = 'test1', `pages_detail_url` = 'test1' WHERE `seo_meta_tags`.`id` = 4; UPDATE `seo_meta_tags` SET `page_title` = 'test1', `page_brief` = 'test1', `page_description` = 'test1', `page_url` = 'test1', `pages_detail_url` = 'test1' WHERE `seo_meta_tags`.`id` = 5; UPDATE `seo_meta_tags` SET `page_title` = 'test1', `page_brief` = 'test1', `page_description` = 'test1', `page_url` = 'test1', `pages_detail_url` = 'test1' WHERE `seo_meta_tags`.`id` = 6; UPDATE `seo_meta_tags` SET `page_title` = 'test1', `page_brief` = 'test1', `page_description` = 'test1', `page_url` = 'test1', `pages_detail_url` = 'test1' WHERE `seo_meta_tags`.`id` = 7;

-- Added on 07 Feb 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `admins` ADD `otp` VARCHAR(6) NULL AFTER `address`, ADD `otp_expiry_time` DATETIME NULL AFTER `otp`;

-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `login_token` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `login_token`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `login_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- 3. -----------------------------------
ALTER TABLE `packages` ADD `days_nights_city` TEXT NULL AFTER `video_link`;
ALTER TABLE `packages` ADD `days` INT NULL DEFAULT '0' AFTER `video_link`, ADD `nights` INT NULL DEFAULT '0' AFTER `days`;

-- Added on 08 Feb 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders` ADD `order_type` TINYINT NULL DEFAULT '1' COMMENT '1=package,2=pay-online' AFTER `order_no`; --(Suraj)

INSERT INTO `seo_meta_tags` (`id`, `page_title`, `page_brief`, `page_description`, `page_url`, `page_detail_url`, `identifier`, `meta_title`, `meta_keyword`, `meta_description`, `other_meta_tag`, `image`, `status`, `created_at`, `updated_at`) VALUES ('0', 'Tour Category', 'Tour Category', 'Tour Category', 'tour-category', 'tour-category', 'tour_category', 'Tour Category | GrandIndiaTour', 'Tour Category | GrandIndiaTour Keyword', 'Tour Category | GrandIndiaTour Description', 'Tour Category | GrandIndiaTour', NULL, '1', '2023-02-01 18:14:12', '2023-02-06 12:00:30');

ALTER TABLE `team_management` CHANGE `first_name` `title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `last_name` `sub_title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
-- Added on 08 Feb 2023 (Pradeepk)
ALTER TABLE `testimonials` ADD `title` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `testimonials` ADD `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `title`;
-- Added on 09 Feb 2023 (Pradeepk)
ALTER TABLE `blogs` ADD `source_title` VARCHAR(255) NULL DEFAULT NULL AFTER `sort_order`, ADD `source_url` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `source_title`, ADD `add_media` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `source_url`, ADD `post_title_url` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `add_media`, ADD `show_comments` TINYINT(1) NULL DEFAULT NULL AFTER `post_title_url`, ADD `allow_comments` TINYINT(1) NULL DEFAULT NULL AFTER `show_comments`;
-- Added on 10 Feb 2023 (Pradeepk)
ALTER TABLE `testimonials` ADD `position_in_company` VARCHAR(255) NULL DEFAULT NULL AFTER `location`, ADD `company_name` VARCHAR(255) NULL DEFAULT NULL AFTER `position_in_company`, ADD `client_link` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `company_name`, ADD `website` VARCHAR(255) NULL DEFAULT NULL AFTER `client_link`, ADD `email` VARCHAR(100) NULL DEFAULT NULL AFTER `website`;
ALTER TABLE `testimonials` ADD `sort_order` INT(11) NULL DEFAULT NULL AFTER `featured`;


---Payment gateway table
CREATE TABLE `payment_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `perameter_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perameter_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perameter_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perameter_4` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perameter_5` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perameter_6` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perameter_7` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `payment_gateways` (`id`, `name`, `value`, `payment_method_id`, `user_id`, `status`, `perameter_1`, `perameter_2`, `perameter_3`, `perameter_4`, `perameter_5`, `perameter_6`, `perameter_7`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Cash On Delivery', 'cod', 1, 1, 1, '', '', '', '', '', '', '', NULL, '2023-01-11 08:03:00', '2023-01-20 12:17:49'),
(2, 'Bank Payment', '', 7, 1, 0, '', '', '', '', '', '', '', '200123014506-bank_payment.png', '2023-01-11 08:03:00', '2023-01-20 12:16:54'),
(3, 'RazorPay', 'razorpay', 6, 1, 1, 'rzp_test_ViWlyGcajdAvMo', 'TPuAjh1rLapqoPAyLbN0RqAL', '', '', '', '', '', NULL, '2023-01-11 08:03:00', '2023-01-20 07:43:06'),
(4, 'paypal', 'paypal', 1, 1, 1, 'sandbox', 'PAYPAL10000', 'PAYPAL33000', '', '', '', '', NULL, NULL, '2023-01-20 12:21:50'),
(5, 'payumoney', 'payumoney', 4, 1, 1, 'UGwBcu', 'rHqSlahJA6edVeFeBS8reon1b9QUhYHd', '', '', '', '', '', NULL, NULL, '2023-01-20 07:42:58'),
(6, 'stripe', 'stripe', 6, 1, 1, 'pk_test_jErm5hmKEGjzzga3hsQyrCAc00bV89rP6x', 'sk_test_ETdDPCnFzX74iPKYZ7FUEnn400ozOkeatK1', '', '', '', '', '', NULL, NULL, '2023-01-20 07:45:15'),
(7, 'Tech Process', 'tpsl', 0, 0, 1, 'test', 'T873705', '4626840924QKHRVW', '4033685904ADSTJH', 'FIRST', '', '', NULL, NULL, '2023-02-10 10:28:56');
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

ALTER TABLE `payment_gateways` ADD `show` TINYINT(2) NULL AFTER `status`;

-- Added on 13 Feb 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders` ADD `package_name` VARCHAR(255) NULL AFTER `package_id`;
ALTER TABLE `orders` ADD `package_type_name` VARCHAR(255) NULL AFTER `package_type`;
ALTER TABLE `enquiries` CHANGE `package` `package_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `enquiries` ADD `package_name` VARCHAR(255) NULL AFTER `package_id`;

-- Added on 14 Feb 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `packages` ADD `is_deleted` INT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `package_inclusion_lists` CHANGE `title` `title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `destinations` ADD `is_deleted` INT NULL DEFAULT '0' AFTER `featured`;
ALTER TABLE `destination_type` ADD `slug` VARCHAR(255) NULL AFTER `name`;
ALTER TABLE `destination_type` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `destinations` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `package_price_info` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `packages` CHANGE `sort_order` `sort_order` INT NULL DEFAULT '0';
ALTER TABLE `packages` CHANGE `destination_id` `destination_id` INT(11) NULL;
ALTER TABLE `itenaries` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `itenaries` ADD `brief` TEXT NULL AFTER `itenary_inclusions`;
ALTER TABLE `itenaries` ADD `tags` TEXT NULL AFTER `description`;
ALTER TABLE `package_info` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `themes` ADD `is_deleted` INT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `themes` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `themes` CHANGE `brief` `brief` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `package_themes` CHANGE `created_at` `created_at` DATETIME NULL;
ALTER TABLE `packages` ADD `tags` TEXT NULL AFTER `days_nights_city`;

-- Added on 14 Feb 2023 (Suraj)
-- 1. -----------------------------------
ALTER TABLE `blogs` ADD `is_deleted` TINYINT(1) NULL AFTER `status`;

ALTER TABLE `blog_categories` ADD `is_deleted` TINYINT(1) NULL AFTER `meta_description`;

-- --------------------------------------------------------
CREATE TABLE `blog_to_categories` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `blog_to_categories`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `blog_to_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
ALTER TABLE `blogs` CHANGE `posted_by` `posted_by` INT(11) NULL;
ALTER TABLE `testimonials` ADD `is_deleted` TINYINT NULL AFTER `status`;
ALTER TABLE `blogs` CHANGE `post_by` `post_by` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
-- Added on 14 Feb 2023 (pradeepk)

ALTER TABLE `blog_categories` ADD `hot_categories` TINYINT(1) NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `testimonials` CHANGE `location` `destination_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `testimonials` CHANGE `destination_id` `destination_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `testimonials` CHANGE `destination_id` `destination` INT(11) NULL DEFAULT NULL;
ALTER TABLE `packages` ADD `inladakh` INT(6) NULL DEFAULT NULL COMMENT '1=inladakh 2=\"outsideladakh' AFTER `status`;
ALTER TABLE `accommodations` CHANGE `name` `name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `testimonials` ADD `pages_title` VARCHAR(255) NULL DEFAULT NULL AFTER `destination_id`, ADD `pages_description` VARCHAR(255) NULL DEFAULT NULL AFTER `pages_title`, ADD `pages_keywords` VARCHAR(255) NULL DEFAULT NULL AFTER `pages_description`;
ALTER TABLE `accommodations` ADD `pages_keywords` VARCHAR(255) NULL DEFAULT NULL AFTER `meta_description`;

ALTER TABLE `accommodations` CHANGE `meta_title` `pages_title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `meta_description` `pages_description` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `destination_info` ADD `type` VARCHAR(255) NULL DEFAULT NULL AFTER `title`, ADD `brief` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `type`, ADD `featured` TINYINT(1) NULL DEFAULT NULL AFTER `brief`;
ALTER TABLE `destination_info` CHANGE `type` `type` INT(11) NULL DEFAULT NULL;

ALTER TABLE `testimonials` ADD `is_deleted` TINYINT NULL AFTER `status`;
ALTER TABLE `blogs` CHANGE `post_by` `post_by` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;


ALTER TABLE `blogs` CHANGE `posted_by` `posted_by` INT(11) NULL;

ALTER TABLE `testimonials` ADD `is_deleted` TINYINT NULL AFTER `status`;
ALTER TABLE `blogs` CHANGE `post_by` `post_by` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
-- Added on 14 Feb 2023 (pradeepk)

ALTER TABLE `blog_categories` ADD `hot_categories` TINYINT(1) NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `testimonials` CHANGE `location` `destination_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `testimonials` CHANGE `destination_id` `destination_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `testimonials` CHANGE `destination_id` `destination` INT(11) NULL DEFAULT NULL;
ALTER TABLE `packages` ADD `inladakh` INT(6) NULL DEFAULT NULL COMMENT '1=inladakh 2=\"outsideladakh' AFTER `status`;
ALTER TABLE `accommodations` CHANGE `name` `name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `testimonials` ADD `pages_title` VARCHAR(255) NULL DEFAULT NULL AFTER `destination_id`, ADD `pages_description` VARCHAR(255) NULL DEFAULT NULL AFTER `pages_title`, ADD `pages_keywords` VARCHAR(255) NULL DEFAULT NULL AFTER `pages_description`;
ALTER TABLE `accommodations` ADD `pages_keywords` VARCHAR(255) NULL DEFAULT NULL AFTER `meta_description`;

ALTER TABLE `accommodations` CHANGE `meta_title` `pages_title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `meta_description` `pages_description` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `destination_info` ADD `type` VARCHAR(255) NULL DEFAULT NULL AFTER `title`, ADD `brief` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `type`, ADD `featured` TINYINT(1) NULL DEFAULT NULL AFTER `brief`;
ALTER TABLE `destination_info` CHANGE `type` `type` INT(11) NULL DEFAULT NULL;
-- Added on 15 Feb 2023 (pradeepk)

ALTER TABLE `accommodation_info` ADD CONSTRAINT `additionalinfo_accommodation_id` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `accommodations` CHANGE `pages_title` `meta_title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `pages_description` `meta_description` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `pages_keywords` `meta_keywords` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `testimonials` CHANGE `pages_title` `meta_title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `pages_description` `meta_description` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `pages_keywords` `meta_keywords` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

-- Table structure for table `accommodation_info`
--

CREATE TABLE `accommodation_info` (
  `id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `accommodation_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `additionalinfo_accommodation_id` (`accommodation_id`);

ALTER TABLE `accommodation_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `accommodation_info`
  ADD CONSTRAINT `additionalinfo_accommodation_id` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`);
COMMIT;

--Added on 15 Feb 2023 (Tasleem)
-- 1. -----------------------------------
CREATE TABLE `smtp_settings` (
  `id` int(11) NOT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `from_mail` varchar(50) DEFAULT NULL,
  `mail_host` varchar(100) DEFAULT NULL,
  `mail_port` int(11) DEFAULT NULL,
  `mail_username` varchar(100) DEFAULT NULL,
  `mail_password` text,
  `mail_encryption` varchar(100) DEFAULT NULL,
  `email_charset` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `smtp_settings`
--

INSERT INTO `smtp_settings` (`id`, `from_name`, `from_mail`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `email_charset`, `created_at`, `updated_at`) VALUES
(1, 'GrandIndiaTour', 'aman@ehostinguk.com', 'smtp.gmail.com', 587, 'aman@ehostinguk.com', 'nroabaaztwelhirq', 'tls', 'utf-8', '2023-02-14 13:26:50', '2023-02-14 13:26:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

-- 2. -----------------------------------
ALTER TABLE `itenaries` ADD `tags` TEXT NULL AFTER `itenary_inclusions`;


--3 ------

ALTER TABLE `accommodations` ADD `is_deleted` TINYINT(1) NULL AFTER `featured`;
-- Added on 15 Feb 2023 (Braham)
-- 1. -----------------------------------
DROP TABLE ` destination_locations `
ALTER TABLE `destinations` ADD `is_city` INT NULL DEFAULT '0' AFTER `parent_id`;
ALTER TABLE `destinations` CHANGE `destination_name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


--2 -----

ALTER TABLE `accommodation_info` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;

ALTER TABLE `accommodation_facilities` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;

ALTER TABLE `accommodations` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;

ALTER TABLE `cms_pages` ADD `type` ENUM('cat', 'page') NULL AFTER `id`;

-- Added on 15 Feb 2023 (pradeepk)
ALTER TABLE `package_info` ADD `brief` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `title`;

ALTER TABLE `accommodation_info` ADD `brief` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `title`;

ALTER TABLE `accommodation_info` ADD `image` VARCHAR(255) NULL DEFAULT NULL AFTER `description`;

ALTER TABLE `destination_info` ADD `image` VARCHAR(255) NULL DEFAULT NULL AFTER `description`;

ALTER TABLE `accommodations` DROP FOREIGN KEY destination_id;
ALTER TABLE `package_accommodations` CHANGE `created_at` `created_at` DATETIME NULL;
ALTER TABLE `common_images` ADD `status` INT NULL DEFAULT '1' AFTER `is_default`;
ALTER TABLE `common_images` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
-- Added on 16 Feb 2023 (pradeepk)

ALTER TABLE `blogs` CHANGE `post_by` `post_by` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `accommodations` ADD `banner_image` VARCHAR(255) NULL AFTER `image`;
ALTER TABLE `packages` CHANGE `package_detail_banners` `banner_image` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;


---17/02/2023
ALTER TABLE `common_images` CHANGE `category_id` `category` VARCHAR(25) NULL DEFAULT NULL;

-- Added on 20 Feb 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `users` CHANGE `status` `status` TINYINT(1) NULL DEFAULT '0';
ALTER TABLE `cms_pages` ADD `is_deleted` INT NULL DEFAULT '0' AFTER `featured`;
ALTER TABLE `cms_pages` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `cms_pages` CHANGE `brief` `brief` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

-- Added on 20 Feb 2023 (Pradeepk)
ALTER TABLE `seo_meta_tags` ADD `pages_url_label` VARCHAR(255) NULL DEFAULT NULL AFTER `page_description`;
ALTER TABLE `seo_meta_tags` CHANGE `pages_url_label` `page_url_label` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
INSERT INTO `email_templates` (`id`, `title`, `slug`, `subject`, `content`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'Test Email', 'test-email', 'Testing Email', '<p>{text}</p>', '1', '2023-02-14 15:58:04', '2023-02-15 17:12:53')
INSERT INTO `email_templates` (`id`, `title`, `slug`, `subject`, `content`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'Testimonial Email', 'testimonial-email', 'Testimonial Grand India Tour', '<meta charset=\"utf-8\">\r\n<title></title>\r\n<link href=\"https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\" />\r\n<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro&amp;display=swap\" rel=\"stylesheet\" />\r\n<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table align=\"center\" bgcolor=\"#ffffff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #ddd;\" width=\"800\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr>\r\n <td colspan=\"2\" style=\"padding: 30px 0px 0 40px;\"><span style=\"font-size: 24px; color: #3f4041; font-family: \'Roboto\', sans-serif, Arial;\">Hello there!</span>\r\n <p style=\"font-size: 16px; font-family: \'Roboto\', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;\">Your have an new testimonials , details given below:</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>TItle : </strong> {title}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Name : </strong> {name}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Email : </strong> {email}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Company Name : </strong> {company_name}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Position in company: </strong> {position_in_company}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Website: </strong> {website}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Content : </strong> {description}</p>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n</table>', '1', '2023-02-20 17:41:27', '2023-02-20 17:41:27')


---21/02/2023

ALTER TABLE `payment_gateways` CHANGE `perameter_1` `perameter_1` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `orders` ADD `partial_amount` DECIMAL(15,2) NULL AFTER `total_amount`, ADD `pay_type` VARCHAR(100) NULL AFTER `partial_amount`;


---27/02/2023

ALTER TABLE `enquiries` ADD `refer_url` TEXT NULL AFTER `form_data`;

---28/02/2023

ALTER TABLE `price_categories` ADD `display_title` VARCHAR(255) NULL AFTER `name`;
UPDATE `price_categories` SET `display_title`=`name`

-- Added on 01 March 2023 (pradeepk)

ALTER TABLE `banner_images` CHANGE `status` `status` INT(11) NULL DEFAULT '1';

---28/02/2023

ALTER TABLE `payment_gateways` ADD `sort_order` INT NULL AFTER `show`, ADD `details` VARCHAR(255) NULL AFTER `sort_order`;


-- Added on 02 Mar 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders_traveller` CHANGE `orders_id` `order_id` INT(11) NOT NULL;
ALTER TABLE `orders_traveller` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `orders_traveller` ADD `save_passenger_details` INT NULL DEFAULT '0' AFTER `ssrSeatInfos`;
ALTER TABLE `orders_traveller` CHANGE `dob` `dob` DATE NULL, CHANGE `passport_nationality` `passport_nationality` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `passport_no` `passport_no` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `passport_exp_date` `passport_exp_date` DATE NULL;
ALTER TABLE `orders` ADD `gst_number` VARCHAR(100) NULL AFTER `price_category_choice_record`, ADD `gst_company` VARCHAR(100) NULL AFTER `gst_number`, ADD `gst_email` VARCHAR(100) NULL AFTER `gst_company`, ADD `gst_phone` VARCHAR(100) NULL AFTER `gst_email`, ADD `gst_address` VARCHAR(100) NULL AFTER `gst_phone`;
ALTER TABLE `orders` ADD `flight_details` LONGTEXT NULL AFTER `gst_address`;

ALTER TABLE `module_discount_category` ADD `module_name` VARCHAR(25) NULL AFTER `id`;

---02/03/2023
---New Email templates

INSERT INTO `email_templates` (`id`, `title`, `slug`, `subject`, `content`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'Transaction Successfull Email', 'transaction-successfull-email', 'Payment Of {package_name}', '<meta charset=\"utf-8\">\r\n<link href=\"https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\" />\r\n<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro&amp;display=swap\" rel=\"stylesheet\" />\r\n<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table align=\"center\" bgcolor=\"#ffffff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #ddd;\" width=\"800\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr style=\"background-color: #fff6ec;\">\r\n <td align=\"left\" colspan=\"2\" style=\"padding: 20px 40px; border-bottom: 1px solid #00000017;\">\r\n <div style=\"float: left;\"><a href=\"{{url(\'/\')}}\"><img alt=\"logo\" src=\"{logo}\" style=\"height: 54px;\" /></a></div>\r\n\r\n <div style=\"font-size: 18px; text-align: right; float: right; margin-top: 5px; color: #952a25; font-weight: 500;\">Payment Details<br />\r\n Date - <!--?php date_default_timezone_set(\"Asia/Thimphu\"); \r\n echo date(\'d M Y\'); ?--> at (<!--?php echo date(\'H:i A\'); ?-->)</div>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td colspan=\"2\" style=\"padding: 30px 0px 0 40px;\"><span style=\"font-size: 24px; color: #3f4041; font-family: \'Roboto\', sans-serif, Arial;\">Hello there!</span>\r\n <p style=\"font-size: 16px; font-family: \'Roboto\', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;\">You have a new payment details, Please find the details below:</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Transaction Id : </strong> {transaction_id}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Sub Total Amount : </strong> {sub_total_amount}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Date : </strong> {payment_date}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Total Amount : </strong> {total_amount}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment Method : </strong> {method}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment No : </strong> {order_no}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Package Name : </strong> {package_name}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment Status : </strong>{payment_status}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payer Name: </strong> {payer_name}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Comment: </strong>{comment}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment Account Payer Name: </strong> {payment_acc_payer_name}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment Account Payer Email: </strong> {payer_email}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Email: </strong> {email}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Paypal ID: </strong> {paypal_id}</p>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n</table>', '1', '2023-03-02 15:50:42', '2023-03-02 15:55:37'), (NULL, 'User Password Change', 'user-password-change', 'Password changed', '<meta charset=\"utf-8\">\r\n<link href=\"https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\" />\r\n<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro&amp;display=swap\" rel=\"stylesheet\" />\r\n<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table align=\"center\" bgcolor=\"#ffffff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #ddd;\" width=\"800\">\r\n <tbody>\r\n <tr>\r\n <td>&nbsp;\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr style=\"background-color: #fff6ec;\">\r\n <td align=\"left\" colspan=\"2\" style=\"padding: 20px 40px; border-bottom: 1px solid #00000017;\">\r\n <div style=\"float: left;\"><a href=\"{{url(\'/\')}}\"><img alt=\"logo\" src=\"{logo}\" style=\"height: 54px;\" /></a></div>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td colspan=\"2\" style=\"padding: 30px 0px 0 40px;\"><span style=\"font-size: 24px; color: #3f4041; font-family: \'Roboto\', sans-serif, Arial;\">Hello {name}!</span>\r\n <p style=\"font-size: 16px; font-family: \'Roboto\', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;\">Your password has been changed successfully.</p>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n</table>', '1', '2023-03-02 15:17:56', '2023-03-02 15:17:56'), (NULL, 'Book Now Enquiry', 'book-now-enquiry', 'Book Now Us Enquiry', '<meta charset=\"utf-8\">\r\n<link href=\"https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\" />\r\n<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro&amp;display=swap\" rel=\"stylesheet\" />\r\n<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table align=\"center\" bgcolor=\"#ffffff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #ddd;\" width=\"800\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr style=\"background-color: #fff6ec;\">\r\n <td align=\"left\" colspan=\"2\" style=\"padding: 20px 40px; border-bottom: 1px solid #00000017;\">\r\n <div style=\"float: left;\"><a href=\"{{url(\'/\')}}\"><img alt=\"logo\" src=\"{logo}\" style=\"height: 54px;\" /></a></div>\r\n\r\n <div style=\"font-size: 18px; text-align: right; float: right; margin-top: 5px; color: #952a25; font-weight: 500;\">Booking Enquiry<br />\r\n Date - <!--?php date_default_timezone_set(\"Asia/Thimphu\"); \r\n echo date(\'d M Y\'); ?--> at (<!--?php echo date(\'H:i A\'); ?-->)</div>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td colspan=\"2\" style=\"padding: 30px 0px 0 40px;\"><span style=\"font-size: 24px; color: #3f4041; font-family: \'Roboto\', sans-serif, Arial;\">Hello there!</span>\r\n <p style=\"font-size: 16px; font-family: \'Roboto\', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;\">You have a new booking, Please find the details below:</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Name : </strong> {order_name}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Email : </strong> {order_email}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Phone : </strong> {order_phone}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>City : </strong> {order_city}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Address1 : </strong> {order_address1}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Address2 : </strong> {order_address2}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>State : </strong> {order_state}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Country : </strong>{order_country_name}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Zip Code : </strong> {order_zip_code}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Trip Date : </strong> {order_trip_date}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Package Type: </strong>{order_packageprice_title}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Price: </strong> {ordersub_total_amount}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Final Price: </strong> {order_total_amount}</p>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n</table>', '1', '2023-03-02 14:41:58', '2023-03-02 14:41:58'), (NULL, 'Bank Transfer Details Email', 'bank-transfer-details-email', 'Payment Of {package_title}', '<meta charset=\"utf-8\">\r\n<title></title>\r\n<link href=\"https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\" />\r\n<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro&amp;display=swap\" rel=\"stylesheet\" />\r\n<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table align=\"center\" bgcolor=\"#ffffff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #ddd;\" width=\"800\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr style=\"background-color: #fff6ec;\">\r\n <td align=\"left\" colspan=\"2\" style=\"padding: 20px 40px; border-bottom: 1px solid #00000017;\">\r\n <div style=\"float: left;\"><a href=\"{{url(\'/\')}}\"><img alt=\"logo\" src=\"{logo}\" style=\"height: 54px;\" /></a></div>\r\n\r\n <div style=\"font-size: 18px; text-align: right; float: right; margin-top: 5px; color: #952a25; font-weight: 500;\">Bank Transfer Details<br />\r\n Date - <!--?php date_default_timezone_set(\"Asia/Thimphu\"); \r\n echo date(\'d M Y\'); ?--> at (<!--?php echo date(\'H:i A\'); ?-->)</div>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td colspan=\"2\" style=\"padding: 30px 0px 0 40px;\"><span style=\"font-size: 24px; color: #3f4041; font-family: \'Roboto\', sans-serif, Arial;\">Hello there!</span>\r\n <p style=\"font-size: 16px; font-family: \'Roboto\', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;\">You have a new bank transfer details, Please find the details below:</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Sub Total Amount : </strong> {sub_total_amount}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Total Amount : </strong> {total_amount}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment Method : </strong> {method}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment No : </strong> {order_no}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Package Name : </strong> {itemname}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment Status : </strong>{status}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Comment: </strong>{comment}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Payment Account Payer Email: </strong> {payer_email}</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Bank Transfer Account Details: </strong> {bank_details}</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Bank Transfer ID: </strong> Test</p>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n</table>', '1', '2023-03-02 12:41:35', '2023-03-02 12:41:35')


-- Added on 03 Mar 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders` ADD `bookingId` VARCHAR(100) NULL AFTER `flight_details`;

---02/03/2023
---New Email template

INSERT INTO `email_templates` (`id`, `title`, `slug`, `subject`, `content`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'Customer Review Email', 'customer-review-email', 'Customer Review Us Feedback', '<meta charset=\"utf-8\">\r\n<link href=\"https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\" />\r\n<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro&amp;display=swap\" rel=\"stylesheet\" />\r\n<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table align=\"center\" bgcolor=\"#ffffff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #ddd;\" width=\"800\">\r\n <tbody>\r\n <tr>\r\n <td>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody>\r\n <tr style=\"background-color: #fff6ec;\">\r\n <td align=\"left\" colspan=\"2\" style=\"padding: 20px 40px; border-bottom: 1px solid #00000017;\">\r\n <div style=\"float: left;\"><a href=\"{{url(\'/\')}}\"><img alt=\"logo\" src=\"logo\" style=\"height: 54px;\" /></a></div>\r\n\r\n <div style=\"font-size: 18px; text-align: right; float: right; margin-top: 5px; color: #952a25; font-weight: 500;\">Customer Review Us<br />\r\n Date - <!--?php date_default_timezone_set(\"Asia/Thimphu\"); \r\n echo date(\'d M Y\'); ?--> at (<!--?php echo date(\'H:i A\'); ?-->)</div>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td colspan=\"2\" style=\"padding: 30px 0px 0 40px;\"><span style=\"font-size: 24px; color: #3f4041; font-family: \'Roboto\', sans-serif, Arial;\">Hello there!</span>\r\n <p style=\"font-size: 16px; font-family: \'Roboto\', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;\">You have a new customer review enquiry, details given below:</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Customer Name: </strong> your_name</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Customer Address: </strong> address</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Customer E-Mail: </strong> email</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Courteous: </strong> courteous</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Helpful: </strong> helpful</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Informative: </strong> informative</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Guide Comments: </strong> guide_comments</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Sightseeing Tour: </strong> sightseeing_tour</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Sight Tour Comments: </strong> sight_tour_comments</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Driver: </strong> driver</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Vehicle: </strong> vehicle</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Transportation Comment: </strong> transportation_comment</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Comfort: </strong> comfort</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Services: </strong> services</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Food: </strong> food</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Accommodation Comments: </strong> accommodation_comments</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Foods: </strong> foods</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Camp Staff: </strong> camp_staff</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Pony Yak: </strong> pony_yak</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Trekking Comments: </strong> trekking_comments</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>On Tour: </strong> on_tour</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>On Trek: </strong> on_trek</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Garbage Disposal Comments: </strong> garbage_disposal_coomments</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Staff On The Trip: </strong> staff_on_the_trip</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Trip Expectations: </strong> trip_expectations</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Staff On The Trip name: </strong> name</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>If So Why: </strong> if_so_why</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Highlight Of Trip: </strong> highlight_of_trip</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Low Point: </strong> low_point</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Overall Comments: </strong> overall_comments</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Trip Name Duration: </strong> trip_name_duration</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Local Guide Name: </strong> local_guide_name</p>\r\n </td>\r\n <td style=\"padding: 10px 0px 10px 40px;\">\r\n <p style=\"color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;\"><strong>Driver Name: </strong> driver_name</p>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n</table>', '1', '2023-03-03 11:03:42', '2023-03-03 11:25:34')


ALTER TABLE `airport_codes_master` ADD `sort_order` INT NULL DEFAULT '0' AFTER `countrycode`, ADD `featured` INT NULL DEFAULT '0' AFTER `sort_order`;
ALTER TABLE `airport_codes_master` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;

-- Added on 13 Mar 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `package_types` CHANGE `package_type` `package_type` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `destination_info` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `accommodations` CHANGE `contact_phone` `contact_phone` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

--07-03-2023==
ALTER TABLE `user_agent_info` CHANGE `agency_age` `agency_age` VARCHAR(55) NULL DEFAULT NULL, CHANGE `no_of_employees` `no_of_employees` VARCHAR(70) NULL DEFAULT NULL, CHANGE `region` `region` VARCHAR(70) NULL DEFAULT NULL;

ALTER TABLE `orders` ADD `agent_id` INT NULL AFTER `user_id`;
ALTER TABLE `users` ADD `group_id` INT NULL AFTER `id`;
ALTER TABLE `users` ADD `approved_agent` TINYINT(1) NULL DEFAULT '0' AFTER `group_id`;


--16-03-2023---

ALTER TABLE `seo_meta_tags` ADD `is_disable` TINYINT(1) NULL DEFAULT '0' AFTER `status`;

ALTER TABLE `orders_traveller` ADD `passport_issue_date` DATE NULL AFTER `passport_exp_date`;
ALTER TABLE `payment_gateways` ADD `is_online` INT NOT NULL DEFAULT '1' AFTER `user_id`;


ALTER TABLE `smtp_settings` CHANGE `mail_gateway` `mail_gateway` CHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `team_management` CHANGE `trip_theme` `trip_theme` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `team_management` CHANGE `email` `email` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

--16-03-2023---

ALTER TABLE `faqs` ADD `tbl_id` INT NULL AFTER `id`, ADD `tbl_category` VARCHAR(25) NULL AFTER `tbl_id`, ADD `tbl_name` VARCHAR(255) NULL AFTER `tbl_category`;

ALTER TABLE `destinations` ADD `is_international` TINYINT(1) NULL DEFAULT '0' AFTER `destination_type`;


---17-03-2023---
ALTER TABLE `itenaries` ADD `meal_option` VARCHAR(255) NULL AFTER `location_id`;

-- Added on 17 Mar 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `airport_codes_master` ADD `status` INT NULL DEFAULT '1' AFTER `featured`;

-- Added on 17 Mar 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `enquiries_master` ADD `category` VARCHAR(20) NULL AFTER `name`, ADD `description` VARCHAR(50) NULL AFTER `category`;

-- Added on 20 Mar 2023 (Suraj)

ALTER TABLE `package_accommodations` ADD `itenary_data` VARCHAR(255) NULL AFTER `accommodation_id`, ADD `accommodation_data` VARCHAR(255) NULL AFTER `itenary_data`;

-- Added on 20 Mar 2023 (Pradeepk)

ALTER TABLE `packages` CHANGE `tour_type` `tour_type` ENUM('group','fixed','open','general') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'fixed';

ALTER TABLE `users` ADD `confirm_password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `password`;
ALTER TABLE `users` ADD `is_agent` INT NULL AFTER `id`;
ALTER TABLE `users` ADD `is_vendor` TINYINT(1) NULL DEFAULT '0' AFTER `is_agent`;

ALTER TABLE `packages` ADD `vendor_id` INT NULL AFTER `is_activity`;

ALTER TABLE `package_accommodations` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);


-- Added on 21 Mar 2023

ALTER TABLE `enquiries_master_group` ADD `slug` VARCHAR(255) NULL AFTER `name`;

-- Added on 21 Mar 2023
ALTER TABLE `enquiries_master` ADD `type` VARCHAR(50) NULL AFTER `name`;


---- 22-03-23 (Suraj) //multiple hotel and day

UPDATE package_accommodations set itenary_data = concat('["',itenary_id,'"]') WHERE `itenary_id` != 0 and `itenary_data` IS NULL;
UPDATE package_accommodations set `accommodation_data` = concat('["',`accommodation_id`,'"]') WHERE `accommodation_id` != 0 and `accommodation_data` IS NULL;


-- Added on 17 Mar 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `orders` ADD `booking_details` LONGTEXT NULL AFTER `bookingId`;

ALTER TABLE `packages` CHANGE `video_link` `video_link` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

INSERT INTO package_flags (package_id,flag_id)
SELECT id,inladakh
FROM packages;

ALTER TABLE `seo_meta_tags` ADD `agent_discount` TINYINT(1) NULL DEFAULT '0' AFTER `image`;

-- Added on 29 Mar 2023 (Suraj)

ALTER TABLE `payment_gateways` ADD `display_name` VARCHAR(55) NOT NULL AFTER `name`;
UPDATE `payment_gateways` set display_name = name;
-- Added on 31 Mar 2023 (Suraj)

ALTER TABLE `website_settings` ADD `is_dedicated` TINYINT(1) NULL DEFAULT '0' AFTER `type`;

-- Added on 31 Mar 2023 (Tasleem)

ALTER TABLE `enquiries` DROP `sub_destination`;
ALTER TABLE `enquiries` ADD `other_destination` VARCHAR(255) NULL AFTER `destination`;
ALTER TABLE `enquiries` ADD `other_package` VARCHAR(255) NULL AFTER `package_name`;
ALTER TABLE `enquiries` ADD `address` TEXT NULL AFTER `email`;

-- Added on 03 Apr 2023 (Tasleem)

ALTER TABLE `enquiries` ADD `accommodation_comment` VARCHAR(255) NULL AFTER `accommodation`;
ALTER TABLE `enquiries` ADD `other_activity` VARCHAR(255) NULL AFTER `activity`;
ALTER TABLE `enquiries` ADD `meal_plans` TEXT NULL AFTER `followup_date`;
ALTER TABLE `enquiries` ADD `date_of_travel` DATETIME NULL AFTER `followup_date`;
ALTER TABLE `enquiries` ADD `inclusions` TEXT NULL AFTER `content`;
ALTER TABLE `enquiries` ADD `no_of_pax` INT NULL AFTER `meal_plans`, ADD `adu_abo_12` INT NULL AFTER `no_of_pax`, ADD `chi_6_12` INT NULL AFTER `adu_abo_12`, ADD `chi_2_5` INT NULL AFTER `chi_6_12`, ADD `infants_upto_2` INT NULL AFTER `chi_2_5`;

CREATE TABLE `accommodation_type` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `accommodation_type` (`id`, `title`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Deluxe', 1, 1, '2023-04-03 13:07:18', '2023-04-03 13:25:43'),
(3, 'Economy', 2, 1, '2023-04-03 13:25:37', '2023-04-03 13:25:37'),
(4, 'Luxury', 3, 1, '2023-04-03 13:25:55', '2023-04-03 13:25:55');

ALTER TABLE `accommodation_type`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `accommodation_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;


CREATE TABLE `enquiries_accommodations` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) DEFAULT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `enquiries_accommodations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `enquiries_accommodations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;


-- Added on 04 Apr 2023 (Tasleem)

CREATE TABLE `enquiries_accommodation_types` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) DEFAULT NULL,
  `accommodation_type_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `enquiries_accommodation_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `enquiries_accommodation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

-- Added on 05 Apr 2023 (Suraj)

ALTER TABLE `cab_route` ADD `duration` VARCHAR(100) NULL AFTER `destination`;

-- Added on 06 Apr 2023 (Pradeepk)

ALTER TABLE `destinations` ADD `show` TINYINT(1) NULL DEFAULT 1 AFTER `featured`;

-- Added on 06 Apr 2023 (Suraj)

ALTER TABLE `accommodation_locations` ADD `is_default` TINYINT(1) NULL DEFAULT '0' AFTER `destination_location_id`;

-- Added on 10 Apr 2023 (Pradeepk)

CREATE TABLE `cab_cities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `sort_order` int(11) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cab_cities`
--
ALTER TABLE `cab_cities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cab_cities`
--
ALTER TABLE `cab_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
-- Added on 12 Apr 2023 (Suraj)

ALTER TABLE `cab_route` CHANGE `origin` `origin` INT NULL DEFAULT NULL, CHANGE `destination` `destination` INT NULL DEFAULT NULL;
-- Added on 12 Apr 2023 (Pradeepk)

ALTER TABLE `cab_route` ADD `cab_city_id` INT(11) NULL DEFAULT NULL AFTER `cab_route_group_id`;

-- Added on 13 Apr 2023 (Pradeepk)

UPDATE `form_attributes` SET `validations` = 'regex:/^\\d{4,12}$/' WHERE `form_attributes`.`id` = 3;

ALTER TABLE `team_management` CHANGE `gender` `gender` TINYINT(1) NULL DEFAULT NULL;

ALTER TABLE `team_management` CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;


-- Added on 14 Apr 2023 (Tasleem)

ALTER TABLE `orders` ADD `last_payment_id` INT NULL AFTER `order_type`;

CREATE TABLE `order_payments` (
  `id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) NOT NULL,
  `payment_channel` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'RazorPay or any other',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_detail` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'cheque- bank, cheqno',
  `ch_account_holder` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ch_account_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ch_bank_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ch_branch_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ch_amount` decimal(15,2) DEFAULT NULL,
  `ch_account_type` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ch_no` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ch_date` date DEFAULT NULL,
  `ch_file` mediumtext COLLATE utf8mb4_unicode_ci,
  `refunded_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `refund_note` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pg_order_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'order_id from payment gateway',
  `pg_payment_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'payment_id from payment gateway',
  `pg_response` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'response from payment gateway',
  `pg_description` text COLLATE utf8mb4_unicode_ci,
  `pg_payment_status` tinyint(1) NOT NULL COMMENT 'payment status from payment gateway',
  `settlement_status` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settlement_utr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settlement_date` datetime DEFAULT NULL,
  `transfer_status` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_date` datetime DEFAULT NULL,
  `pg_created_at` int(11) DEFAULT NULL,
  `pg_response_date` datetime DEFAULT NULL COMMENT 'datetime of response from payment gateway',
  `transfer_bank_account` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_bank_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_sub_account` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

ALTER TABLE `order_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- Added on 14 Apr 2023 (Braham)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `package_inventory` (
  `id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `price_id` int(11) DEFAULT NULL,
  `trip_date` date DEFAULT NULL,
  `inventory` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `package_inventory`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `package_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


INSERT INTO package_inventory (package_id, price_id, trip_date, inventory, status, created_at, updated_at) SELECT package_id, (select from_date from package_departures pd where pd.package_id=pdc.package_id limit 1) as trip_date, package_price_id, capacity, '1' as 'status', '2023-04-15 00:00:00' as 'created_at', '2023-04-15 00:00:00' as 'updated_at' FROM package_departure_capacities pdc

-- Added on 17 Apr 2023 (Braham)
ALTER TABLE `form_elements` ADD `enquiryMapping` VARCHAR(255) NULL AFTER `classNameInner`;
ALTER TABLE `enquiries` ADD `no_of_days` INT NULL AFTER `meal_plans`;
UPDATE `form_elements` SET `enquiryMapping` = type WHERE type IN ('name','phone','email');

-- Added on 18 Apr 2023 (Braham)
ALTER TABLE `price_category_elements` ADD `sort_order` INT NULL DEFAULT '0' AFTER `input_choices`;
ALTER TABLE `price_category_elements` ADD `updated_at` DATETIME NULL AFTER `created_at`;
ALTER TABLE `price_category_elements` CHANGE `created_at` `created_at` DATETIME NULL;

-- Added on 18 Apr 2023 (Suraj)
ALTER TABLE `cab_cities` ADD `is_airport` TINYINT(1) NULL AFTER `featured`;

-- Added on 20 Apr 2023 (Braham)
ALTER TABLE `cab_route` ADD FOREIGN KEY (`origin`) REFERENCES `cab_cities`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `cab_route` ADD FOREIGN KEY (`destination`) REFERENCES `cab_cities`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `cab_route` ADD FOREIGN KEY (`cab_route_group_id`) REFERENCES `cab_route_group`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `cab_route` DROP `cab_city_id`;
ALTER TABLE `cab_route_inventory` ADD FOREIGN KEY (`cab_route_group_id`) REFERENCES `cab_route_group`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `cab_route_inventory` ADD FOREIGN KEY (`cab_id`) REFERENCES `cab_master`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `cab_route_price` ADD FOREIGN KEY (`cab_route_group_id`) REFERENCES `cab_route_group`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `cab_route_price` ADD FOREIGN KEY (`cab_id`) REFERENCES `cab_master`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `cab_route_price` ADD FOREIGN KEY (`cab_route_id`) REFERENCES `cab_route`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--20April 2023
ALTER TABLE `cab_master` ADD `sort_order` INT NOT NULL AFTER `status`;
ALTER TABLE `cab_master` ADD `equivalent` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `cab_route_price` ADD `round_trip_price` DECIMAL(15,2) NOT NULL AFTER `price`;
ALTER TABLE `cab_route` ADD `distance` INT NULL AFTER `duration`;

-- Added on 25 Apr 2023 (Suraj)

CREATE TABLE `package_slots` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `duration_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `package_slots`
--
ALTER TABLE `package_slots`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `package_slots`
--
ALTER TABLE `package_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


ALTER TABLE `package_inventory` ADD `slot_id` INT NULL DEFAULT NULL AFTER `price_id`;


-- Added on 25 Apr 2023 (Suraj)

ALTER TABLE `cab_route` ADD `description` TEXT NULL AFTER `places`;

-- Added on 26 Apr 2023 (Suraj)
ALTER TABLE `package_slots` ADD `status` TINYINT(2) NULL DEFAULT '1' AFTER `start_time`;

-- Added on 26 Apr 2023 (Suraj)
ALTER TABLE `cab_route_group` ADD `inclusion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `cab_data`, ADD `exclusion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `inclusion`, ADD `term` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT NULL AFTER `exclusion`;

ALTER TABLE `orders` ADD `country_code` INT NULL AFTER `phone`;


-- Added on 27 Apr 2023 (Suraj)
ALTER TABLE `email_templates` ADD `email_type` VARCHAR(10) NOT NULL AFTER `content`, ADD `bcc_admin` TINYINT NOT NULL COMMENT '0=No,1=Yes' AFTER `email_type`;
ALTER TABLE `email_templates` CHANGE `email_type` `email_type` ENUM('customer','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;


-- Added on 27 Apr 2023 (Suraj)
ALTER TABLE `package_slots` ADD `created_at` DATETIME NOT NULL AFTER `status`, ADD `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`;

-- Added on 02 May 2023 (Braham)
ALTER TABLE `users` ADD `country_code` INT NULL AFTER `phone`;

-- Added on 02 May 2023 (Suraj)
ALTER TABLE `seo_meta_tags` ADD `description` TEXT NULL AFTER `identifier`;

-- Added on 02 May 2023 (Tasleem)
ALTER TABLE `destinations` ADD `package_meta_title` VARCHAR(650) NULL AFTER `meta_description`, ADD `package_meta_keyword` VARCHAR(650) NULL AFTER `package_meta_title`, ADD `package_meta_description` VARCHAR(650) NULL AFTER `package_meta_keyword`, ADD `activity_meta_title` VARCHAR(650) NULL AFTER `package_meta_description`, ADD `activity_meta_keyword` VARCHAR(650) NULL AFTER `activity_meta_title`, ADD `activity_meta_description` VARCHAR(650) NULL AFTER `activity_meta_keyword`;

-- Added on 02 May 2023 (Suraj)
ALTER TABLE `accommodation_room` ADD `room_type_name` VARCHAR(55) NULL AFTER `room_type_id`, ADD `single_bed` TINYINT NULL AFTER `room_type_name`, ADD `double_bed` TINYINT NULL AFTER `single_bed`, ADD `extra_bed` TINYINT NULL AFTER `double_bed`, ADD `base_occupancy` TINYINT NULL AFTER `extra_bed`, ADD `max_occupancy` TINYINT NULL AFTER `base_occupancy`;
ALTER TABLE `accommodation_room` CHANGE `room_type_id` `room_type_id` INT(11) NULL;

-- Added on 03 May 2023 (Suraj)
ALTER TABLE `accommodation_room` ADD `base_price` DECIMAL(15,2) NULL AFTER `updated_at`, ADD `price_extra_adult` DECIMAL(15,2) NULL AFTER `base_price`, ADD `extra_child_bed` TINYINT NULL AFTER `price_extra_adult`, ADD `price_extra_child_1` DECIMAL(15,2) NULL AFTER `extra_child_bed`, ADD `price_extra_child_2` DECIMAL(15,2) NULL AFTER `price_extra_child_1`;
ALTER TABLE `accommodation_room` CHANGE `extra_bed` `extra_adult_bed` TINYINT(4) NULL DEFAULT NULL;
-- Added on 04 May 2023 (Suraj)

CREATE TABLE `accommodation_inventory` (
  `id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `inventory` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `extra_adult` decimal(15,2) DEFAULT NULL,
  `extra_child_1` decimal(15,2) DEFAULT NULL,
  `extra_child_2` decimal(15,2) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodation_inventory`
--
ALTER TABLE `accommodation_inventory`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodation_inventory`
--
ALTER TABLE `accommodation_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- Added on 04 May 2023 (Suraj)

CREATE TABLE `accommodation_plans` (
  `id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `plan_name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meal_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodation_plans`
--
ALTER TABLE `accommodation_plans`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodation_plans`
--
ALTER TABLE `accommodation_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Added on 05 May 2023 (Suraj)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `order_service_voucher` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `name_of_pax` varchar(55) NOT NULL,
  `pax_contact` varchar(20) NOT NULL,
  `local_contact` varchar(20) NOT NULL,
  `agent_contact` varchar(55) NOT NULL,
  `title` varchar(55) DEFAULT NULL,
  `trip_type` varchar(25) DEFAULT NULL,
  `gst_no` varchar(30) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `cab_data` text DEFAULT NULL,
  `hotel_data` text NOT NULL,
  `vehicle_type` varchar(155) NOT NULL,
  `flight_data` text NOT NULL,
  `payment_mode` varchar(55) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
ALTER TABLE `order_service_voucher`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `order_service_voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- Added on 05 May 2023 (Tasleem) (queue related tables)

ALTER TABLE `smtp_settings` ADD `is_queue` TINYINT(2) NOT NULL DEFAULT '0' AFTER `email_charset`;


CREATE TABLE `email_error_logs` (
  `id` int(11) NOT NULL,
  `msg_subject` varchar(255) NOT NULL,
  `msg_body` text NOT NULL,
  `attachments` text NOT NULL,
  `msg_from` varchar(255) NOT NULL,
  `msg_to` varchar(255) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `added_by` varchar(50) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `record_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `error_message` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `email_error_logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `email_error_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;


CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);
COMMIT;


CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

-- Added on 09 May 2023 (Suraj)

ALTER TABLE `order_service_voucher` ADD `package_data` TEXT NULL AFTER `email`;

-- Added on 11 May 2023 (Suraj)

ALTER TABLE `accommodation_plans` ADD `plan_json_data` TEXT NULL AFTER `meal_type`;

-- Added on 12 May 2023 (Abhishek)
ALTER TABLE `user_agent_info` ADD `pan_no` VARCHAR(120) NULL DEFAULT NULL AFTER `company_brand`;
ALTER TABLE `user_agent_info` ADD `pan_image` VARCHAR(255) NULL DEFAULT NULL AFTER `pan_no`;


-- Added on 12 May 2023 (Suraj)

CREATE TABLE `user_wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance` int(11) NOT NULL,
  `commant` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `for_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_wallet`
--
ALTER TABLE `user_wallet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_wallet`
--
ALTER TABLE `user_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--Added on 15 May 2023 (Abhishek)
ALTER TABLE `user_agent_info` ADD `whatsapp_country_code` INT NULL DEFAULT NULL AFTER `whatsapp_phone`;

--Added on 16 May 2023 (Suraj)
ALTER TABLE `user_wallet` CHANGE `created_at` `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

--Added on 16 May 2023 (Suraj)
ALTER TABLE `cab_route` ADD `discount_category_id` INT NULL AFTER `route_type`;


--Added on 16 May 2023 (Braham)
ALTER TABLE `accommodations` ADD `discount_category_id` INT NOT NULL AFTER `is_deleted`;
ALTER TABLE `accommodations` ADD `search_price` DECIMAL(15,2) NOT NULL AFTER `is_deleted`;

--Added on 16 May 2023 (Tasleem)
ALTER TABLE `user_wallet` ADD `txn_id` INT NULL AFTER `user_id`;
ALTER TABLE `user_wallet` CHANGE `txn_id` `txn_id` VARCHAR(20) NULL DEFAULT NULL;

ALTER TABLE `accommodations` CHANGE `discount_category_id` `discount_category_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `user_wallet` CHANGE `commant` `comment` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;


--Added on 18 May 2023 (Braham)
ALTER TABLE `enquiries_interactions` CHANGE `created_type` `created_type` ENUM('backend','customer','agent','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'customer';


--Added on 18 May 2023 (Suraj)

ALTER TABLE `user_wallet` CHANGE `created_at` `created_at` DATETIME NULL;
ALTER TABLE `user_wallet` CHANGE `updated_at` `updated_at` DATETIME NULL;

--Added on 19 May 2023 (Abhishek)
ALTER TABLE `cab_route` ADD `sort_order` INT NOT NULL AFTER `status`;

--Added on 19 May 2023 (Tasleem)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `temp_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `is_agent` tinyint(1) DEFAULT 0,
  `is_vendor` tinyint(1) DEFAULT 0,
  `group_id` int(11) DEFAULT NULL,
  `approved_agent` tinyint(1) DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `country_code` int(11) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `confirm_password` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `verify_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `otp` varchar(6) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `forgot_token` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `temp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);
ALTER TABLE `temp_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `temp_users` DROP INDEX `users_email_unique`;

--Added on 22 May 2023 (Braham)
ALTER TABLE `users` CHANGE `created_at` `created_at` DATETIME NULL, CHANGE `updated_at` `updated_at` DATETIME NULL;
--delete confirm_password from user table

--Added on 22 May 2023 (Abhishek)
ALTER TABLE `accommodations` ADD `checkin_time` TIME NOT NULL AFTER `rating`;
ALTER TABLE `accommodations` ADD `checkout_time` TIME NOT NULL AFTER `checkin_time`;


--Added on 22 May 2023 (Tasleem)
ALTER TABLE `package_inclusion_lists` ADD `image` VARCHAR(255) NULL AFTER `title`;

--Added on 23 May 2023 (Suraj)
ALTER TABLE `accommodation_features` ADD `icon` VARCHAR(255) NULL AFTER `title`, ADD `is_featured` TINYINT(1) NULL AFTER `icon`;


--Added on 23 May 2023 (Tasleem)
ALTER TABLE `orders` ADD `fees` DECIMAL(10,2) NULL DEFAULT '0.00' AFTER `sub_total_amount`;



--Added on 23 May 2023 (Tasleem)
ALTER TABLE `user_wallet` ADD `fees` DECIMAL(15,2) NULL AFTER `amount`, ADD `payment_method` VARCHAR(20) NULL AFTER `fees`;

--Added on 24 May 2023 (Suraj)
ALTER TABLE `cab_route` ADD `sharing` TINYINT(1) NOT NULL DEFAULT '0' AFTER `cab_route_group_id`, ADD `featured` TINYINT(1) NOT NULL DEFAULT '0' AFTER `sharing`;
ALTER TABLE `cab_route` ADD `start_time` VARCHAR(55) NOT NULL AFTER `cab_route_group_id`;
ALTER TABLE `cab_route_group` ADD `sharing` TINYINT NULL DEFAULT '0' AFTER `cab_data`;

ALTER TABLE `user_agent_info` ADD `gst_no` VARCHAR(120) NULL DEFAULT NULL AFTER `company_brand`;
ALTER TABLE `user_agent_info` ADD `gst_image` VARCHAR(255) NULL DEFAULT NULL AFTER `company_brand`;


--Added on 25 May 2023 (Suraj)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `cab_route_to_group` (
  `id` int(11) NOT NULL,
  `cab_route_id` int(11) NOT NULL,
  `cab_route_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `cab_route_to_group`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `cab_route_to_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `cab_route_to_group`(`cab_route_id`, `cab_route_group_id`) SELECT id,cab_route_group_id
FROM cab_route


--Added on 26 May 2023 (Suraj)
ALTER TABLE `accommodation_room` ADD `total_room` INT NULL AFTER `room_features`, ADD `room_view` INT NULL AFTER `total_room`, ADD `bed_type` INT NULL AFTER `room_view`, ADD `extra_bed_type` INT NULL AFTER `bed_type`, ADD `single_price` DECIMAL(15,2) NULL AFTER `extra_bed_type`;

--Added on 26 May 2023 (Braham)
ALTER TABLE `accommodations` ADD `vendor_id` INT NOT NULL DEFAULT '0' AFTER `name`;


--Added on 26 May 2023 (Suraj)
ALTER TABLE `accommodation_inventory` ADD `single_price` DECIMAL(15,2) NULL AFTER `price`;
--Added on 26 May 2023 (Suraj)
INSERT INTO `sitemap_updates` (`id`, `name`, `slug`, `comment`, `created_at`, `updated_at`) VALUES (NULL, 'Activities Sitemap', 'activities', 'activities', '2023-05-26 13:09:24.000000', '2023-05-26 13:09:24.000000');

--Added on 29 May 2023 (Suraj)
ALTER TABLE `cab_route` ADD `image` VARCHAR(255) NULL AFTER `featured`;
--Added on 30 May 2023 (Suraj)
ALTER TABLE `accommodation_room` ADD `max_adult_bed` TINYINT(4) NULL AFTER `base_occupancy`, ADD `base_child_no` TINYINT(4) NULL AFTER `max_adult_bed`, ADD `max_child_no` TINYINT(4) NULL AFTER `base_child_no`;

--Added on 30 May 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `user_wallet` CHANGE `balance` `balance` DECIMAL(15,2) NOT NULL;

-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `airline_price_markup` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `markup_type` varchar(10) NOT NULL,
  `markup_value` int(11) NOT NULL,
  `markup_value_nonc` int(11) NOT NULL,
  `max_base_price` int(11) NOT NULL,
  `is_domestic` int(11) NOT NULL,
  `featured` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `airline_price_markup`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `airline_price_markup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


--Added on 31 May 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `media_manager` ADD `alt_text` VARCHAR(255) NOT NULL AFTER `caption`;

-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `agent_airline_markup` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `markup_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `markup_value` int(11) NOT NULL,
  `markup_value_nonc` int(11) NOT NULL,
  `max_base_price` int(11) NOT NULL,
  `is_domestic` int(11) NOT NULL,
  `featured` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `agent_airline_markup`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `agent_airline_markup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--Added on 31 May 2023 (Tasleem)
ALTER TABLE `user_agent_info` ADD `agent_logo` VARCHAR(100) NULL AFTER `bookings_per_month`;


ALTER TABLE `api_call_log` CHANGE `created_at` `created_at` DATETIME NOT NULL, CHANGE `updated_at` `updated_at` DATETIME NOT NULL;


--Added on 1 June 2023 (Braham)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `flight_commission` (
  `id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `markup_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `markup_value` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `flight_commission` (`id`, `code`, `name`, `markup_type`, `markup_value`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'domestic', 'Domestic', 'fixed', 100, 0, 1, '2023-05-30 18:02:58', '2023-06-01 13:00:37'),
(2, 'international', 'International', 'percent', 2, 1, 1, '2023-05-30 18:02:59', '2023-06-01 13:00:37');
ALTER TABLE `flight_commission`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `flight_commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

ALTER TABLE `orders` ADD `markup` DECIMAL(15,2) NOT NULL AFTER `pay_type`, ADD `commission` DECIMAL(15,2) NOT NULL AFTER `markup`;
ALTER TABLE `orders` ADD `markup_details` TEXT NOT NULL AFTER `markup`;
ALTER TABLE `orders` ADD `commission_details` TEXT NOT NULL AFTER `commission`;
ALTER TABLE `orders` CHANGE `created_at` `created_at` DATETIME NOT NULL, CHANGE `updated_at` `updated_at` DATETIME NOT NULL;

--Added on 1 June 2023 (Suraj)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `room_master` (
  `id` int(11) NOT NULL,
  `type` enum('room_view','bed_type','extra_bed_type') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `room_master`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `room_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


--Added on 1 June 2023 (Suraj)

ALTER TABLE `accommodations` ADD `total_room` INT NULL AFTER `rating`;



--Added on 1 June 2023 (Braham)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `order_amendments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `bookingId` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amendmentId` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amendment_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_json` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `response_json` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amendment_details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `order_amendments`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `order_amendments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `orders` ADD `status` VARCHAR(100) NOT NULL AFTER `booking_details`;

--Added on 5 June 2023 (Braham)
ALTER TABLE `orders` ADD `supplier_markup` DECIMAL(15,2) NOT NULL AFTER `pay_type`;
ALTER TABLE `orders` ADD `hide_markup` INT NOT NULL AFTER `markup_details`;


--Added on 06 June 2023 (Tasleem)
CREATE TABLE `sms_templates` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sms_templates` (`id`, `title`, `slug`, `template_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
(25, 'Cab Booking SMS', 'cab-booking-sms', '1107168571094960699', 'Cab - Your cab details for Pickup: {#var#} from {#var#} Time:{#var#}; Drop: {#var#} on {#var#} Booking ID: {#var#} Customer: {#var#} - overlandescape.com', 1, '2023-06-05 13:50:42', '2023-06-05 14:58:25'),
(26, 'Flight Booking SMS', 'flight-booking-sms', '1107168571098210324', 'Flight - Your ticket details for Sector : {#var#} Dep:{#var#} Arr: {#var#} Flight: {#var#} PNR: {#var#} Customer: {#var#} overlandescape.com', 1, '2023-06-05 13:51:15', '2023-06-05 14:58:16'),
(27, 'Flight Cancellation SMS', 'flight-cancellation-sms', '1107168571102243949', 'The cancellation has been processed for order with reference no : {#var#}, PNR={#var#}, Sector={#var#},Passenger Name-{#var#}, Total Cancelled Passenger={#var#} -overlandescape.com', 1, '2023-06-05 13:51:33', '2023-06-05 14:57:55'),
(28, 'Hotel Booking SMS', 'hotel-booking-sms', '1107168571105717565', 'Hotel Booking Confirmation: Booking Confirmed With Booking ID {#var#} From {#var#} to {#var#} in {#var#}. Guest Name : {#var#} - overlandescape.com', 1, '2023-06-05 13:51:53', '2023-06-05 14:58:07'),
(29, 'Holiday Booking SMS', 'holiday-booking-sms', '1107168571108466063', 'Holiday Booking Confirmation: Booking confirmed with Booking ID: {#var#} Destination: {#var#}, Travel Date : {#var#} to {#var#}, Package :{#var#}, Traveller Name:  {#var#} - overlandescape.com', 1, '2023-06-05 13:52:32', '2023-06-05 14:57:23'),
(30, 'Activity Booking SMS', 'activity-booking-sms', '1107168571114698765', 'Activity Booking Confirmation: Booking confirmed for {#va#r} with Booking ID: {#var#} Date : {#var#}, Time: {#var#}, Duration: {#var#}, Participants :{#var#}� overlandescape.com', 1, '2023-06-05 13:52:52', '2023-06-05 14:56:38'),
(31, 'Wallet SMS Update', 'wallet-sms-update', '1107168571120259730', 'Wallet Update Confirmation: Your deposit balance has been updated by Rs.{#var#}  on {#var#}. Your Current Total Deposit Balance is Rs.{#var#}- overlandescape.com', 1, '2023-06-05 13:53:19', '2023-06-05 14:56:25');

ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sms_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;


INSERT INTO `website_settings` (`id`, `type`, `is_dedicated`, `label`, `name`, `value`, `old_value`, `field_type`, `css_class`, `created_by`, `updated_by`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'website', '1', 'SMS_PASSWORD', 'SMS_PASSWORD', 'Q!mCo4$7@22', NULL, 'text', NULL, '0', '0', '0', NULL, '2023-05-31 15:51:52', '2023-05-31 15:54:51'), (NULL, 'website', '1', 'SMS_SENDERID', 'SMS_SENDERID', 'OELADK', NULL, 'text', NULL, '0', '0', '0', NULL, '2023-05-31 15:51:52', '2023-06-05 15:27:41'), (NULL, 'website', '1', 'SMS_USERNAME', 'SMS_USERNAME', 'overlandescape', NULL, 'text', NULL, '0', '0', '0', NULL, '2023-05-31 15:51:52', '2023-05-31 15:54:51');

--Added on 06 June 2023 (Suraj)
ALTER TABLE `package_inventory` ADD `booked` INT NOT NULL DEFAULT '0' AFTER `inventory`;
--Added on 06 June 2023 (Suraj)
ALTER TABLE `cab_route_inventory` ADD `booked` INT NULL AFTER `inventory`;
--Added on 06 June 2023 (Suraj)
ALTER TABLE `accommodation_inventory` ADD `booked` INT NULL AFTER `inventory`;

--Added on 06 June 2023 (Tasleem)
ALTER TABLE `customer_reviews` ADD `trip_date` VARCHAR(50) NULL DEFAULT NULL AFTER `trip_name_duration`; 

--Added on 07 June 2023 (Suraj)
---remove service_level from order table

--Added on 07 June 2023 (Suraj)
ALTER TABLE `orders` ADD `inventory` INT NULL AFTER `comment`;

--Added on 07 June 2023 (Suraj)
ALTER TABLE `accommodation_room` CHANGE `base_occupancy` `base_occupancy` INT(11) NULL DEFAULT NULL, CHANGE `max_adult_bed` `max_adult_bed` INT(11) NULL DEFAULT NULL, CHANGE `base_child_no` `base_child_no` INT(11) NULL DEFAULT NULL, CHANGE `max_child_no` `max_child_no` INT(11) NULL DEFAULT NULL, CHANGE `max_occupancy` `max_occupancy` INT(11) NULL DEFAULT NULL;

--Added on 09 June 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `accommodations` ADD `booking_mode` INT NOT NULL COMMENT '0=>Automatic booking mode, 1=>Booking needs approval' AFTER `discount_category_id`;

-- 2. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `airline_faretypes` (
  `id` int(11) NOT NULL,
  `fare_types` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ui` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `airline_faretypes` (`id`, `fare_types`, `description`, `color`, `ui`, `api`) VALUES
(1, 'Sale', 'Sale fare/ Promotional fare', 'Blue', 'Sale', 'SALE'),
(2, 'Published fare', 'Regular Fare', 'Peach', 'Published', 'PUBLISHED'),
(3, 'SME', 'In Indigo/Goair GST number is mandatory for booking', 'Orange', 'SME', 'SME'),
(4, 'Corporate', 'Free seat/Free Meal benefits, less cancellation fee and Reissue fee', 'Green', 'Published', 'CORPORATE'),
(5, 'Flexi Fare', 'Lower Cancellation & Date Change Fee, Free Meal, Free Standard Seats, 50% Off on XL Seats. ', 'Peach', 'Flexi', 'FLEXI'),
(6, 'Coupon', 'Promotional fare', 'Peach', 'Coupon', 'COUPON'),
(7, 'Family Fare', 'More than 4 passengers needs to be travel', 'Peach', 'Family', 'FAMILY'),
(8, 'Tactical', 'Promotional fare/ Coupon Fare', 'Peach', 'Tactical', 'TACTICAL'),
(9, 'Premium', 'Same as Flexi Fare', 'Peach', 'Premium Flex', 'PREMIUM_FLEX'),
(10, 'Flexi', 'Lower Cancellation & Date Change Fee, Free Meal, Free Standard Seats, 50% OFF on XL Seats.', 'Peach', 'Flexi', 'FLEXI'),
(11, 'Gomore', 'Lower Cancellation & Date Change Fee', 'Peach', 'Gomore', 'GOMORE'),
(12, 'Corporate Flex', 'Free seat/ Free Meal benefits, less cancellation fee and Reissue fee', 'Green', 'Published', 'CORPORATE_FLEX'),
(13, 'Flexi plus', 'Lower Cancellation & Date Change Fee, Free Meal, Free Standard Seats, 50% OFF on XL Seats.', 'Peach', 'Flexi Plus', 'FLEXl_PLUS'),
(14, 'Special Return', 'Regular Fare', 'Peach', 'SPECIAL RETURN', 'SPECIAL_RETURN'),
(15, 'Premium Flex', 'Lower Cancellation & Date Change Fee, Free Meal, Free Standard Seats,50% Off on XL Seats.', 'Peach', 'Premium Flex', 'PREMIUM_FLEX'),
(16, 'Gomore', 'Published Fare', 'Peach', 'Published', 'PUBLISHED'),
(17, 'Flexi Plus', 'Flexi fare', 'Peach', 'Flexi Plus', 'FLEXl_PLUS'),
(18, 'Offer Fare', 'Deal inventory fare', 'Pink', 'Offer Fare', 'OFFER_FARE_WITHOUT_PNR'),
(19, 'Instant offer fare', 'Instant inventory fare', 'Pink', 'Instant Offer Fare', 'OFFER_FARE_WITH_PNR');
ALTER TABLE `airline_faretypes`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `airline_faretypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;


--Added on 12 June 2023 (Braham)
-- 1. -----------------------------------
ALTER TABLE `orders` ADD `supplier_markup_details` TEXT NOT NULL AFTER `supplier_markup`
;
ALTER TABLE `orders` ADD `discount_details` TEXT NOT NULL AFTER `discount`;

--Added on 12 June 2023 (Tasleem)
ALTER TABLE `orders_status_history` CHANGE `orders_status_id` `orders_status` VARCHAR(20) NULL DEFAULT NULL; 
ALTER TABLE `orders_status_history` DROP `customer_notified`, DROP `sms_message`, DROP `sms_status`;
ALTER TABLE `orders_status_history` CHANGE `orders_id` `order_id` INT(11) NOT NULL DEFAULT '0'; 
ALTER TABLE `orders_status_history` CHANGE `date_added` `date_added` DATE NULL; 
ALTER TABLE `orders` ADD `orders_status` INT NULL AFTER `booking_details`; 
ALTER TABLE `orders_status_history` ADD `created_type` ENUM('backend','customer','agent','') NULL DEFAULT 'customer' AFTER `comments`; 
ALTER TABLE `orders_status_history` ADD `created_by` INT NULL AFTER `created_type`; 


ALTER TABLE `accommodations` ADD `policies` TEXT NULL AFTER `description`; 

--Added on 12 June 2023 (Braham)
-- 2. -----------------------------------
RENAME TABLE `flight_commission` TO `flight_commission_12june2023`; 

-- 3. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `flight_commission` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `offer_markup_type` varchar(10) NOT NULL,
  `offer_markup_value` int(11) NOT NULL,
  `online_markup_type` varchar(10) NOT NULL,
  `online_markup_value` int(11) NOT NULL,
  `offer_commission_type` varchar(10) NOT NULL,
  `offer_commission_value` int(11) NOT NULL,
  `online_commission_type` varchar(10) NOT NULL,
  `online_commission_value` int(11) NOT NULL,
  `offer_discount_type` varchar(10) NOT NULL,
  `offer_discount_value` int(11) NOT NULL,
  `online_discount_type` varchar(10) NOT NULL,
  `online_discount_value` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `flight_commission` (`id`, `code`, `name`, `offer_markup_type`, `offer_markup_value`, `online_markup_type`, `online_markup_value`, `offer_commission_type`, `offer_commission_value`, `online_commission_type`, `online_commission_value`, `offer_discount_type`, `offer_discount_value`, `online_discount_type`, `online_discount_value`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'domestic', 'Domestic', 'fixed', 150, 'fixed', 500, 'fixed', 50, 'fixed', 200, 'fixed', 10, 'fixed', 30, 0, 1, '2023-05-30 18:02:58', '2023-06-12 15:52:56'),
(2, 'international', 'International', 'percent', 2, 'percent', 2, 'percent', 2, 'percent', 2, 'percent', 2, 'percent', 2, 1, 1, '2023-05-30 18:02:59', '2023-06-12 15:52:56');
ALTER TABLE `flight_commission`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `flight_commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

-- 4. -----------------------------------
RENAME TABLE `agent_airline_markup` TO `agent_airline_markup_12june2023`;

-- 5. -----------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `agent_airline_markup` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `offer_markup_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `offer_markup_value` int(11) NOT NULL,
  `online_markup_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `online_markup_value` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `agent_airline_markup` (`id`, `agent_id`, `code`, `name`, `offer_markup_type`, `offer_markup_value`, `online_markup_type`, `online_markup_value`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(4, 193, 'international', '', 'percent', 2, 'percent', 10, 0, 0, '2023-05-31 10:59:16', '2023-06-12 13:56:26'),
(5, 193, 'domestic', '', 'fixed', 500, 'fixed', 300, 0, 0, '2023-06-02 14:43:24', '2023-06-12 13:56:26');
ALTER TABLE `agent_airline_markup`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `agent_airline_markup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;


ALTER TABLE `orders` ADD `hide_agent_details` INT NOT NULL AFTER `orders_status`, ADD `hide_price_details` INT NOT NULL AFTER `hide_agent_details`;

--Added on 12 June 2023 (Pradeepk)
ALTER TABLE `blog_categories` CHANGE `meta_title` `meta_title` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `meta_keyword` `meta_keyword` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `meta_description` `meta_description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `blogs` CHANGE `meta_title` `meta_title` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `meta_keyword` `meta_keyword` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `meta_description` `meta_description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;


ALTER TABLE `orders` ADD `supplier_total_amount` DECIMAL(15,2) NOT NULL AFTER `booking_details`;


ALTER TABLE `orders` ADD `admin_markup` DECIMAL(15,2) NOT NULL AFTER `supplier_markup`;
ALTER TABLE `orders` CHANGE `supplier_markup_details` `admin_markup_details` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

--Added on 13 June 2023 (Tasleem)
ALTER TABLE `orders_status_history` DROP `date_added`
ALTER TABLE `orders_status_history` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT NULL, CHANGE `updated_at` `updated_at` DATETIME NULL DEFAULT NULL;
ALTER TABLE `user_agent_info` ADD `created_at` DATETIME NULL AFTER `query`, ADD `updated_at` DATETIME NULL AFTER `created_at`;

ALTER TABLE `testimonials` CHANGE `destination` `destination_id` VARCHAR(255) NULL DEFAULT NULL; 





----------------STOP Please create migration file for DB-----------------

UPDATE `orders` SET `agent_id`= null  WHERE agent_id = 0;

UPDATE `orders` SET `user_id`= agent_id WHERE agent_id > 0;


--Added on 19 June 2023 (Abhishek)
INSERT INTO `modules` (`id`, `parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES (NULL, '0', 'flight', 'Manage Flight', 'Manage Flight', '31', 'view,add,edit,delete', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

--Added on 27 June 2023 (Pradeepk)

ALTER TABLE `website_settings` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `default_value` `default_value` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

UPDATE website_settings SET description = '', default_value = ''

ALTER TABLE `website_settings` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `default_value` `default_value` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `temp_users` CHANGE `is_agent` `is_agent` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0';



--Added on 28 June 2023 (Braham)
UPDATE orders_status_history JOIN admins
    ON orders_status_history.created_by = admins.id AND orders_status_history.created_type = 'backend'
SET orders_status_history.created_by_name = admins.name where created_type = 'backend';

UPDATE orders_status_history JOIN users
    ON orders_status_history.created_by = users.id AND orders_status_history.created_type = 'customer'
SET orders_status_history.created_by_name = users.name where created_type = 'customer';



INSERT INTO `crons` (`id`, `title`, `url`, `frequency`, `details`, `created_at`, `updated_at`) VALUES (NULL, 'Check Order Status', 'https://www.overlandescape.com/crons/check_order_status', 'Every Minute', NULL, '2023-06-28 00:00:00', '2023-06-28 00:00:00');
INSERT INTO `crons` (`id`, `title`, `url`, `frequency`, `details`, `created_at`, `updated_at`) VALUES (NULL, 'Check Order Amendments Status', 'https://www.overlandescape.com/crons/check_order_amendments_status', 'Every Minute', NULL, '2023-06-28 00:00:00', '2023-06-28 00:00:00');


--Added on 29 June 2023 (Braham)
INSERT INTO `email_templates` (`title`, `slug`, `subject`, `content`, `email_type`, `bcc_admin`, `status`, `created_at`, `updated_at`) VALUES
('Insufficient Balance Alert', 'insufficient-balance-alert', 'Insufficient balance alert for booking id {booking_id}', '<p>Hello Admin,</p>\r\n\r\n<p>You have&nbsp;insufficient balance in your tripjack account for booking id {booking_id}<br />\r\n<br />\r\nRegards,</p>', 'admin', 0, 1, '2023-06-29 15:31:57', '2023-06-29 15:40:33');

--Added on 29 June 2023 (Braham)
ALTER TABLE `orders` CHANGE `orders_status` `orders_status` INT(11) NOT NULL DEFAULT '0';  

--Added on 25 July 2023 (Braham)
DELETE FROM `website_settings` WHERE `name` = 'DISABLE_PACKAGE_BOOKING';
DELETE FROM `website_settings` WHERE `name` = 'DISABLE_HOTEL_BOOKING';

--Added on 01 August 2023 (Braham)
INSERT INTO `sms_templates` (`title`, `slug`, `template_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
('Rental Booking SMS', 'rental-booking-sms', '1107169080197756002', 'Rental� - Your bike details for Pickup: {#var1#} from {#var2#} Time:{#var3#}; Booking ID: {#var4#}, Customer: {#var5#} - overlandescape.com', 1, '2023-08-01 18:05:47', '2023-08-01 18:05:47');

--Added on 14 August 2023 (Abhishek)
INSERT INTO `modules` (`id`, `parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES (NULL, '0', 'vendor', 'Manage Vendors', 'Manage Vendors', '31', 'view,add,edit,delete', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
--Added on 26 August 2023 (Pradeepk)

UPDATE website_settings SET name='CONTACT_RESERVATION_OFFICE_IFRAME' where name = 'CONTACT_MAP_IFRAME';

UPDATE website_settings SET name='CONTACT_MAIN_OFFICE_IFRAME' where name = 'CONTACT_MAP_IFRAME2';

INSERT INTO website_settings (type, is_dedicated, label, name, description, value, old_value, default_value, field_type, css_class, created_by, updated_by, sort_order, status, created_at, updated_at) VALUES('website', 0, 'CONTACT_MAIN_OFFICE_IFRAME', 'CONTACT_MAIN_OFFICE_IFRAME', 'CONTACT_MAIN_OFFICE_IFRAME', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3301.2267730046165!2d77.57310251744384!3d34.16611930000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38fdeb39b9990cdd%3A0x62414beb26e63383!2sRaku+Guest+House!5e0!3m2!1sen!2sin!4v1531478667189\" width=\"100%\" height=\"250\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '', '', 'text', '', 0, 0, 0, NULL, '2023-08-25 16:32:27', '2023-08-25 16:32:27'),('website', 0, 'CONTACT_DELHI_OFFICE_IFRAME', 'CONTACT_DELHI_OFFICE_IFRAME', 'CONTACT_DELHI_OFFICE_IFRAME', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d520.8472685718222!2d77.05129675203564!3d28.574902927873215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d1b411289f193%3A0x2341397b14e91f24!2sVardhman+Crown+Mall+Sector+19!5e0!3m2!1sen!2sin!4v1532068676256\" width=\"100%\" height=\"250\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '', '', 'text', '', 0, 0, 0, NULL, '2023-08-25 16:32:56', '2023-08-25 16:32:56');

--Added on 16 August 2023 (Braham)
--Update the hotel room base prices to plan base prices
--admin/tools/syncAccommodationPublishPrice?update=1

--make cms slug unique

--Added on 30 August 2023 (Braham)
UPDATE `agent_discount_module_to_group` set module_name = 'rental' WHERE module_name='bike';
UPDATE `seo_meta_tags` set identifier = 'rental' where identifier = 'bike';
UPDATE `module_discount_category` set module_name = 'rental' WHERE module_name = 'bike';
--Update modules for bike to rental permission
INSERT INTO `modules` (`parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES ('0', 'rental', 'Manage Rental', 'Manage Rental', '31', 'view,add,edit,delete', '2023-08-14 12:29:25', '2023-08-14 12:29:25'); 

UPDATE `accommodation_inventory` set booked = 0 WHERE booked is NULL;
UPDATE `bike_city_inventory` set booked = 0 WHERE booked is NULL;
UPDATE `cab_route_inventory` set booked = 0 WHERE booked is NULL;

--Added on 20 September 2023 (Braham)
INSERT INTO `seo_meta_tags` (`id`, `page_title`, `page_brief`, `page_description`, `page_url_label`, `page_url`, `page_detail_url`, `identifier`, `description`, `meta_title`, `meta_keyword`, `meta_description`, `other_meta_tag`, `image`, `agent_discount`, `module_type`, `admin_email`, `admin_phone`, `online_booking`, `status`, `is_disable`, `created_at`, `updated_at`) VALUES ('0', 'Cms Listing', 'testing', '<p>testing testing</p>', NULL, '', 'content', 'cms_listing', NULL, '', '', '', '', NULL, '1', 'minor', '', '', '0', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

--Added on 20 sep 2023 (Abhishek)
INSERT INTO `modules` (`id`, `parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES (NULL, '0', 'coupons', 'Manage Coupons', 'Manage Coupons', '33', 'view,add,edit,delete', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

--Added on 28 sep 2023 (Abhishek)
UPDATE `modules` SET `permission_names` = 'view,edit' WHERE `modules`.`id` = 23;


--Added on 28 sep 2023 (Abhishek)
ALTER TABLE `enquiries_interactions` DROP FOREIGN KEY `enquiries_interactions_ibfk_1`; ALTER TABLE `enquiries_interactions` ADD CONSTRAINT `enquiries_interactions_ibfk_1` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE; 

--Added on 13 Oct 2023 (Abhishek)
INSERT INTO `modules` (`id`, `parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES (NULL, '0', 'sms_templates', 'Manage SMS', 'Manage SMS', '34', 'view,add,edit,view', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

--Added on 23 Oct 2023 (Pradeepk)
INSERT INTO `seo_meta_tags` (`id`, `page_title`, `page_brief`, `page_description`, `page_url_label`, `page_url`, `page_detail_url`, `identifier`, `description`, `meta_title`, `meta_keyword`, `meta_description`, `other_meta_tag`, `image`, `agent_discount`, `module_type`, `admin_email`, `admin_phone`, `online_booking`, `status`, `is_disable`, `created_at`, `updated_at`) VALUES (NULL, 'Thank You', 'A breathtaking destination that is sure to leave you mesmerized with its natural beauty and vibrant culture. these destinations are located in India, and it is known for its stunning beaches, picturesque landscapes, and rich history.', '<p>test description</p>', NULL, 'thankyou', 'thankyou', 'thank_you', 'Thank You Page - GrandIndiaTour', 'Thank You Page - GrandIndiaTour', 'Thank You Page - GrandIndiaTour', 'Thank You Page - GrandIndiaTour', 'Thank You Page - GrandIndiaTour', NULL, '0', 'minor', NULL, NULL, '0', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
--Added on 25 Oct 2023 (Pradeepk)
INSERT INTO `seo_meta_tags` (`id`, `page_title`, `page_brief`, `page_description`, `page_url_label`, `page_url`, `page_detail_url`, `identifier`, `description`, `meta_title`, `meta_keyword`, `meta_description`, `other_meta_tag`, `image`, `agent_discount`, `module_type`, `admin_email`, `admin_phone`, `online_booking`, `status`, `is_disable`, `created_at`, `updated_at`) VALUES (NULL, 'Contact Us', 'A breathtaking destination that is sure to leave you mesmerized with its natural beauty and vibrant culture. these destinations are located in India, and it is known for its stunning beaches, picturesque landscapes, and rich history.', '<p>test description</p>', '', 'contact-us', 'contact-us', 'contact_us', 'Contact-Us - premviaggindia', 'Contact-Us - premviaggindia', 'Contact-Us - premviaggindia', 'Contact-Us - premviaggindia', 'Contact-Us - premviaggindia', NULL, '0', 'minor', NULL, NULL, '0', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
--Added on 26 Oct 2023 (Pradeepk)

INSERT INTO `seo_meta_tags` (`id`, `page_title`, `page_brief`, `page_description`, `page_url_label`, `page_url`, `page_detail_url`, `identifier`, `description`, `meta_title`, `meta_keyword`, `meta_description`, `other_meta_tag`, `image`, `agent_discount`, `module_type`, `admin_email`, `admin_phone`, `online_booking`, `status`, `is_disable`, `created_at`, `updated_at`) VALUES (NULL, 'Pay Online', 'A breathtaking destination that is sure to leave you mesmerized with its natural beauty and vibrant culture. these destinations are located in India, and it is known for its stunning beaches, picturesque landscapes, and rich history.', '<p>test description</p>', NULL, 'payment/pay_online', 'payment/pay_online', 'pay_online', 'Pay Online - premviaggindia', 'Pay Online - premviaggindia', 'Pay Online - premviaggindia', 'Pay Online - premviaggindia', 'Pay Online - premviaggindia', NULL, '0', 'minor', NULL, NULL, '0', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);



--Added on 09 Nov 2023 (Braham)
-- X-UPDATE `themes` set identifier = 'package' where 1;
-- X-UPDATE `package_inclusion_lists` set identifier = 'package' WHERE 1;
-- X-UPDATE `flags` set identifier = 'package' WHERE 1;

UPDATE `package_airlines` set identifier = 'package' WHERE 1;
UPDATE `package_types` SET identifier = 'package' WHERE 1;
UPDATE `package_settings` set identifier = 'package' WHERE 1;


--Added on 13 Feb 2024 (Braham)
INSERT INTO `airline_faretypes` (`id`, `fare_types`, `description`, `color`, `ui`, `api`) VALUES (NULL, 'Offer Fare', 'Deal inventory fare', 'Pink', 'Offer Fare', 'IIAIR_OFFER_FARE_WITHOUT_PNR'), (NULL, 'Instant offer fare', 'Instant inventory fare', 'Pink', 'Instant Offer Fare', 'IIAIR_OFFER_FARE_WITH_PNR'); 

--Added on 21 Feb 2024 (Braham)
INSERT INTO `modules` (`parent_id`, `name`, `display_name`, `description`, `sort_order`, `permission_names`, `created_at`, `updated_at`) VALUES
(0, 'sms_templates', 'Manage SMS', 'Manage SMS', 34, 'view,add,edit,view', '2023-10-13 11:05:52', '2023-10-13 11:05:52'),
(0, 'quotations', 'Manage Quotation', 'Manage Quotation', 35, 'view,add,edit,delete', '2024-02-02 11:07:53', '2024-02-02 11:07:53');

--Added on 22 Feb 2024 (Braham)
INSERT INTO `website_settings` (`type`, `is_dedicated`, `label`, `name`, `description`, `value`, `old_value`, `default_value`, `field_type`, `css_class`, `created_by`, `updated_by`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
('website', 0, 'Flight Offer Fare Display Timing', 'FLIGHT_OFFER_FARE_DISPLAY_TIMING', 'Flight Offer Fare Display Timing', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"],\"timing\":{\"start_time\":\"10:00:00\",\"end_time\":\"17:00:00\"}}', '', '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"],\"timing\":{\"start_time\":\"10:00:00\",\"end_time\":\"17:00:00\"}}', 'text', '', 0, 0, 0, NULL, '2024-02-22 12:17:41', '2024-02-22 12:47:25');


--Added on 01 March 2024 (Braham)
INSERT INTO `email_templates` (`title`, `slug`, `subject`, `content`, `email_type`, `bcc_admin`, `manager_email`, `contact_email`, `status`, `created_at`, `updated_at`) VALUES
('Request Payment', 'request-payment', 'Action Required: Confirm Your Flight Booking', '<meta charset=\"utf-8\">\r\n<link href=\"https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\" />\r\n<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro&amp;display=swap\" rel=\"stylesheet\" />\r\n<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<table bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:20,&quot;w&quot;:1208,&quot;h&quot;:352,&quot;abs_x&quot;:263,&quot;abs_y&quot;:362}\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tbody bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:20,&quot;w&quot;:1206,&quot;h&quot;:351,&quot;abs_x&quot;:263,&quot;abs_y&quot;:362}\">\r\n   <tr bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:20,&quot;w&quot;:1206,&quot;h&quot;:351,&quot;abs_x&quot;:263,&quot;abs_y&quot;:362}\">\r\n      <td bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:20,&quot;w&quot;:1206,&quot;h&quot;:351,&quot;abs_x&quot;:263,&quot;abs_y&quot;:362}\">\r\n      <table align=\"center\" bgcolor=\"#ffffff\" bis_size=\"{&quot;x&quot;:274,&quot;y&quot;:21,&quot;w&quot;:700,&quot;h&quot;:349,&quot;abs_x&quot;:517,&quot;abs_y&quot;:363}\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #18afa6;\" width=\"700\">\r\n        <tbody bis_size=\"{&quot;x&quot;:274,&quot;y&quot;:22,&quot;w&quot;:698,&quot;h&quot;:347,&quot;abs_x&quot;:517,&quot;abs_y&quot;:364}\">\r\n         <tr bis_size=\"{&quot;x&quot;:274,&quot;y&quot;:22,&quot;w&quot;:698,&quot;h&quot;:347,&quot;abs_x&quot;:517,&quot;abs_y&quot;:364}\">\r\n            <td bis_size=\"{&quot;x&quot;:274,&quot;y&quot;:22,&quot;w&quot;:698,&quot;h&quot;:347,&quot;abs_x&quot;:517,&quot;abs_y&quot;:364}\">\r\n            <table bis_size=\"{&quot;x&quot;:275,&quot;y&quot;:23,&quot;w&quot;:696,&quot;h&quot;:346,&quot;abs_x&quot;:518,&quot;abs_y&quot;:365}\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n             <tbody bis_size=\"{&quot;x&quot;:276,&quot;y&quot;:24,&quot;w&quot;:695,&quot;h&quot;:344,&quot;abs_x&quot;:519,&quot;abs_y&quot;:366}\">\r\n               <tr bis_size=\"{&quot;x&quot;:276,&quot;y&quot;:24,&quot;w&quot;:695,&quot;h&quot;:22,&quot;abs_x&quot;:519,&quot;abs_y&quot;:366}\">\r\n                 <td bis_size=\"{&quot;x&quot;:276,&quot;y&quot;:24,&quot;w&quot;:695,&quot;h&quot;:22,&quot;abs_x&quot;:519,&quot;abs_y&quot;:366}\">{header}</td>\r\n                </tr>\r\n               <tr bis_size=\"{&quot;x&quot;:276,&quot;y&quot;:46,&quot;w&quot;:695,&quot;h&quot;:298,&quot;abs_x&quot;:519,&quot;abs_y&quot;:388}\">\r\n                  <td bis_size=\"{&quot;x&quot;:276,&quot;y&quot;:46,&quot;w&quot;:695,&quot;h&quot;:298,&quot;abs_x&quot;:519,&quot;abs_y&quot;:388}\" colspan=\"2\" style=\"padding: 15px 20px 10px 20px;\">\r\n                  <p bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:75,&quot;w&quot;:653,&quot;h&quot;:25,&quot;abs_x&quot;:540,&quot;abs_y&quot;:417}\"><span bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:78,&quot;w&quot;:37,&quot;h&quot;:19,&quot;abs_x&quot;:540,&quot;abs_y&quot;:420}\" style=\"font-size: 16px; color: #3f4041; font-family: \'Roboto\', sans-serif, Arial;\">Dear&nbsp;</span><strong bis_size=\"{&quot;x&quot;:334,&quot;y&quot;:81,&quot;w&quot;:44,&quot;h&quot;:14,&quot;abs_x&quot;:577,&quot;abs_y&quot;:423}\">{name}</strong></p>\r\n\r\n                 <p bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:113,&quot;w&quot;:653,&quot;h&quot;:20,&quot;abs_x&quot;:540,&quot;abs_y&quot;:455}\">We need your prompt attention to finalize your flight booking.</p>\r\n\r\n                 <p bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:147,&quot;w&quot;:653,&quot;h&quot;:41,&quot;abs_x&quot;:540,&quot;abs_y&quot;:489}\">Your booking with Order ID <strong bis_size=\"{&quot;x&quot;:456,&quot;y&quot;:150,&quot;w&quot;:66,&quot;h&quot;:14,&quot;abs_x&quot;:699,&quot;abs_y&quot;:492}\">{order_no}</strong> made on <strong bis_size=\"{&quot;x&quot;:580,&quot;y&quot;:150,&quot;w&quot;:77,&quot;h&quot;:14,&quot;abs_x&quot;:823,&quot;abs_y&quot;:492}\">{order_date}</strong> with a previous payment of <strong bis_size=\"{&quot;x&quot;:819,&quot;y&quot;:150,&quot;w&quot;:91,&quot;h&quot;:14,&quot;abs_x&quot;:1062,&quot;abs_y&quot;:492}\">{total_amount}</strong> requires a slight adjustment of <strong bis_size=\"{&quot;x&quot;:474,&quot;y&quot;:171,&quot;w&quot;:93,&quot;h&quot;:14,&quot;abs_x&quot;:717,&quot;abs_y&quot;:513}\">{payment_due}</strong> due to a fare increase.</p>\r\n\r\n                  <p bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:202,&quot;w&quot;:653,&quot;h&quot;:20,&quot;abs_x&quot;:540,&quot;abs_y&quot;:544}\">To confirm your booking, please proceed to make the additional payment using the button below:</p>\r\n\r\n                 <p bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:236,&quot;w&quot;:653,&quot;h&quot;:20,&quot;abs_x&quot;:540,&quot;abs_y&quot;:578}\"><a bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:239,&quot;w&quot;:52,&quot;h&quot;:14,&quot;abs_x&quot;:540,&quot;abs_y&quot;:581}\" href=\"{pay_now_link}\">Pay Now</a></p>\r\n\r\n                  <p bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:269,&quot;w&quot;:653,&quot;h&quot;:32,&quot;abs_x&quot;:540,&quot;abs_y&quot;:611}\" style=\"font-size: 14px; font-family: \'Roboto\', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;\"><font bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:277,&quot;w&quot;:53,&quot;h&quot;:16,&quot;abs_x&quot;:540,&quot;abs_y&quot;:619}\" color=\"#3f4041\"><span bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:277,&quot;w&quot;:53,&quot;h&quot;:16,&quot;abs_x&quot;:540,&quot;abs_y&quot;:619}\" style=\"font-size: 14px;\">Regards,</span></font></p>\r\n\r\n                  <p bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:301,&quot;w&quot;:653,&quot;h&quot;:32,&quot;abs_x&quot;:540,&quot;abs_y&quot;:643}\" style=\"font-size: 14px;font-family: \'Roboto\', sans-serif, Arial;color: #51535c;line-height: 32px;margin-bottom: 0;margin-top: 0;\"><font bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:309,&quot;w&quot;:99,&quot;h&quot;:16,&quot;abs_x&quot;:540,&quot;abs_y&quot;:651}\" color=\"#3f4041\"><span bis_size=\"{&quot;x&quot;:297,&quot;y&quot;:309,&quot;w&quot;:99,&quot;h&quot;:16,&quot;abs_x&quot;:540,&quot;abs_y&quot;:651}\" style=\"font-size: 14px;\">Overlandescape Team</span></font></p>\r\n                 </td>\r\n               </tr>\r\n               <tr bis_size=\"{&quot;x&quot;:276,&quot;y&quot;:344,&quot;w&quot;:695,&quot;h&quot;:24,&quot;abs_x&quot;:519,&quot;abs_y&quot;:686}\">\r\n                  <td bis_size=\"{&quot;x&quot;:276,&quot;y&quot;:344,&quot;w&quot;:695,&quot;h&quot;:24,&quot;abs_x&quot;:519,&quot;abs_y&quot;:686}\" colspan=\"2\">\r\n                  <p bis_size=\"{&quot;x&quot;:277,&quot;y&quot;:345,&quot;w&quot;:693,&quot;h&quot;:22,&quot;abs_x&quot;:520,&quot;abs_y&quot;:687}\" style=\"color: #3a3a3c;font-size: 14px;font-weight: 400;font-family: Roboto;margin: 0;\">{contact_details}</p>\r\n                 </td>\r\n               </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>', 'customer', 0, 0, 0, 1, '2023-06-21 11:36:43', '2024-03-05 16:24:02');

--Added on 04 March 2024 (Braham)
INSERT INTO `website_settings` (`type`, `is_dedicated`, `label`, `name`, `description`, `value`, `old_value`, `default_value`, `field_type`, `css_class`, `created_by`, `updated_by`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
('website', 0, 'OFFER_FARE_CANCEL_TIME', 'OFFER_FARE_CANCEL_TIME', 'OFFER_FARE_CANCEL_TIME in minutes', '30', '', '30', 'text', '', 0, 0, 0, NULL, '2024-03-04 15:49:51', '2024-03-04 15:49:51');


--Added on 16 April 2024 (Abhishek for International and Domestic Destination)
INSERT INTO `destination_flag` (`id`, `name`, `page_title`, `page_brief`, `page_description`, `slug`, `status`) VALUES (NULL, 'International', 'International Destinations', 'International Destinations', 'International Destinations', 'international', '1');
INSERT INTO `destination_flag` (`id`, `name`, `page_title`, `page_brief`, `page_description`, `slug`, `status`) VALUES (NULL, 'Domestic', 'Domestic Destinations', 'Domestic Destinations', 'Domestic Destinations', 'domestic', '1');

INSERT INTO destination_flags (destination_id, flag_id)
SELECT destinations.id, (SELECT id FROM destination_flag WHERE slug = 'international')
FROM destinations
WHERE destinations.is_international = 1;

INSERT INTO destination_flags (destination_id, flag_id)
SELECT destinations.id, (SELECT id FROM destination_flag WHERE slug = 'domestic')
FROM destinations
WHERE destinations.is_international = 0;

--set cron every 5 minute
--https://www.website.com/crons/check_offer_fare_cancel

--Added on 27 April 2024 (Braham)
UPDATE `packages` SET booking_mode=1 WHERE booking_mode is NULL;
UPDATE `accommodations` SET booking_mode = 1 WHERE booking_mode is NULL;

--Added on 10 June 2024 (Abhishek)
INSERT INTO `seo_meta_tags` (`id`, `page_title`, `page_brief`, `page_description`, `page_url_label`, `page_url`, `page_detail_url`, `identifier`, `description`, `meta_title`, `meta_keyword`, `meta_description`, `other_meta_tag`, `image`, `agent_discount`, `module_type`, `admin_email`, `admin_phone`, `online_booking`, `status`, `is_disable`, `created_at`, `updated_at`) VALUES (NULL, 'Contact Us', 'A breathtaking destination that is sure to leave you mesmerized with its natural beauty and vibrant culture. these destinations are located in India, and it is known for its stunning beaches, picturesque landscapes, and rich history.', '<p>test description</p>', NULL, 'contact-us', 'contact-us', 'contact_us', 'Contact-Us | Overlandescape', 'Contact-Us | Overlandescape', 'Contact-Us | Overlandescape', 'Contact-Us | Overlandescape', NULL, NULL, '0', 'minor', NULL, NULL, '0', '1', '0', current_timestamp(), current_timestamp());
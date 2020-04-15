-- Transactions can be NULL for imports
ALTER TABLE `transactions` CHANGE `date` `date` DATE NULL, CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `description` `description` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `amount` `amount` FLOAT NULL DEFAULT '0';
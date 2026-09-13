ALTER TABLE `addbook`
  ADD COLUMN `city_id` int(10) DEFAULT NULL COMMENT '城市編號' AFTER `myZip`,
  ADD COLUMN `town_id` bigint(20) DEFAULT NULL COMMENT '鄉鎮市區編號' AFTER `city_id`,
  ADD KEY `idx_addbook_member_default` (`emailid`, `setdefault`, `create_date`, `addressid`),
  ADD KEY `idx_addbook_city_town` (`city_id`, `town_id`);

UPDATE `addbook` AS `a`
INNER JOIN (
  SELECT
    `t`.`Post`,
    MIN(`t`.`AutoNo`) AS `city_id`,
    MIN(`t`.`townNo`) AS `town_id`
  FROM `town` AS `t`
  INNER JOIN `city` AS `c` ON `c`.`AutoNo` = `t`.`AutoNo`
  WHERE `t`.`State` = 0 AND `c`.`State` = 0
  GROUP BY `t`.`Post`
  HAVING COUNT(*) = 1
) AS `location` ON `location`.`Post` = `a`.`myZip`
SET
  `a`.`city_id` = `location`.`city_id`,
  `a`.`town_id` = `location`.`town_id`
WHERE `a`.`city_id` IS NULL AND `a`.`town_id` IS NULL;

ALTER TABLE `uorder`
  MODIFY `emailid` int(11) NOT NULL COMMENT '會員編號',
  MODIFY `addressid` int(10) DEFAULT NULL COMMENT '預填來源地址ID，訂單歷史以快照為準',
  MODIFY `howpay` tinyint(4) NOT NULL DEFAULT 1 COMMENT '付款方式：1=貨到付款',
  MODIFY `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '訂單狀態：1=待處理',
  ADD COLUMN `recipient_name` varchar(30) NOT NULL COMMENT '收件人姓名' AFTER `addressid`,
  ADD COLUMN `recipient_phone` varchar(20) NOT NULL COMMENT '收件人手機' AFTER `recipient_name`,
  ADD COLUMN `postal_code` varchar(10) NOT NULL COMMENT '郵遞區號快照' AFTER `recipient_phone`,
  ADD COLUMN `city_name` varchar(150) NOT NULL COMMENT '城市名稱快照' AFTER `postal_code`,
  ADD COLUMN `town_name` varchar(150) NOT NULL COMMENT '鄉鎮市區名稱快照' AFTER `city_name`,
  ADD COLUMN `recipient_address` varchar(200) NOT NULL COMMENT '詳細地址快照' AFTER `town_name`,
  ADD COLUMN `items_subtotal` decimal(12,2) NOT NULL COMMENT '商品小計' AFTER `remark`,
  ADD COLUMN `shipping_fee` decimal(12,2) NOT NULL COMMENT '運費' AFTER `items_subtotal`,
  ADD COLUMN `order_total` decimal(12,2) NOT NULL COMMENT '訂單總額' AFTER `shipping_fee`,
  ADD COLUMN `submission_token_hash` char(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'Checkout submission token SHA-256' AFTER `order_total`,
  ADD UNIQUE KEY `uq_uorder_submission_token` (`submission_token_hash`),
  ADD KEY `idx_uorder_member_created` (`emailid`, `create_date`, `orderid`),
  ADD CONSTRAINT `fk_uorder_member`
    FOREIGN KEY (`emailid`) REFERENCES `member` (`emailid`)
    ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `orderid` varchar(30) NOT NULL,
  `p_id` int(10) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` smallint unsigned NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_items_order` (`orderid`),
  KEY `idx_order_items_product` (`p_id`),
  CONSTRAINT `fk_order_items_order`
    FOREIGN KEY (`orderid`) REFERENCES `uorder` (`orderid`)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT `fk_order_items_product`
    FOREIGN KEY (`p_id`) REFERENCES `product` (`p_id`)
    ON UPDATE RESTRICT ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `order_number_sequences` (
  `sequence_date` date NOT NULL,
  `last_value` int unsigned NOT NULL,
  PRIMARY KEY (`sequence_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

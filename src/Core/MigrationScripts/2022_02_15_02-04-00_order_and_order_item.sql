
ALTER TABLE `up_order`
	ADD CUSTOMER_NAME VARCHAR(255);

ALTER TABLE `up_order`
	ADD PHONE VARCHAR(51);

ALTER TABLE `up_order`
	ADD EMAIL VARCHAR(255);

alter table `up_order`
DROP FOREIGN KEY `up_order_up_item_ID_fk`;

alter table `up_order`
DROP COLUMN `ITEM_ID`;

CREATE TABLE `up_order_item` (
	                             `ORDER_ID` int(11) NOT NULL,
	                             `ITEM_ID` int(11) NOT NULL,
	                             `COUNT` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
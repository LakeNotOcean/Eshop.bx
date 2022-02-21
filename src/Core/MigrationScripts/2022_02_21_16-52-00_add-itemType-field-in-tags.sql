alter table up_tag
	add ITEM_TYPE_ID int null;

alter table up_tag
	add constraint up_tag_up_item_type_ID_fk
		foreign key (ITEM_TYPE_ID) references up_item_type (ID);

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 8;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 2;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 1;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 4;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 3;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 6;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 5;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 7;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 10;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 9;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 12;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 11;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 14;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 13;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 15;

UPDATE eshop.up_tag t
SET t.ITEM_TYPE_ID = 1
WHERE t.ID = 16;



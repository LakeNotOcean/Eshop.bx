ALTER TABLE up_item
ADD ITEM_TYPE_ID INT NOT NULL DEFAULT 1;

ALTER TABLE up_item
ADD CONSTRAINT fk_I_IT FOREIGN KEY (ITEM_TYPE_ID) REFERENCES up_item_type(ID)
ON UPDATE RESTRICT
ON DELETE RESTRICT
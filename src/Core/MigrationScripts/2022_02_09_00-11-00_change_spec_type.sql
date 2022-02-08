ALTER TABLE up_spec_type
DROP COLUMN TYPE;

INSERT INTO eshop.up_spec_type (ID, NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER) VALUES (1, 'Цвет', 1, 1);
INSERT INTO eshop.up_spec_type (ID, NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER) VALUES (2, 'Подсветка', 1, 1);
INSERT INTO eshop.up_spec_type (ID, NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER) VALUES (3, 'Тип подключения', 2, 1);
INSERT INTO eshop.up_spec_type (ID, NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER) VALUES (4, 'Интерфейс подключения', 2, 0);

INSERT INTO eshop.up_spec_category (ID, NAME, DISPLAY_ORDER) VALUES (1, 'Внешний вид', 1);
INSERT INTO eshop.up_spec_category (ID, NAME, DISPLAY_ORDER) VALUES (2, 'Подключение и интерфейсы', 1);
INSERT INTO eshop.up_spec_category (ID, NAME, DISPLAY_ORDER) VALUES (3, 'Спецификации видеопроцессора', 1);
INSERT INTO eshop.up_spec_category (ID, NAME, DISPLAY_ORDER) VALUES (4, 'Спецификации видеопамяти', 1);

INSERT INTO eshop.up_item_type (ID, NAME) VALUES (1, 'videocard');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE) VALUES (2, 1, 'черный');
INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE) VALUES (2, 2, 'нет');
INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE) VALUES (2, 3, 'беспроводная');
INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE) VALUES (2, 4, 'bluetooth');
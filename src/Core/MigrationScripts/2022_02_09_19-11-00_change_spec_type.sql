INSERT INTO eshop.up_item_type (NAME)
VALUES ('Видеокарта');

INSERT INTO eshop.up_item_type (NAME)
VALUES ('Клавиатура');

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Внешний вид', 2);

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Заводские данные', 1);

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Общие параметры', DEFAULT);

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Спецификации видеопамяти', DEFAULT);

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Спецификации видеопроцессора', DEFAULT);

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Вывод изображения', DEFAULT);

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Подключение', DEFAULT);

INSERT INTO eshop.up_spec_category (NAME, DISPLAY_ORDER)
VALUES ('Габариты', DEFAULT);


INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Гарантия', 1, 1);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Страна-производитель', 1, DEFAULT);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Объем видеопамяти', 4, 1);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Тип памяти', 4, DEFAULT);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Частота памяти', 4, DEFAULT);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Тип', 3, 1);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Год релиза', 3, 2);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Длина', 2, 1);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Толщина', 2, 2);

INSERT INTO eshop.up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER)
VALUES ('Подсветка', 2, 3);



INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 1, '24 мес.');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 2, 'Китай');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 3, '2 ГБ');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 4, 'GDDR5');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 5, '5010 МГц');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 6, 'видеокарта');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 8, '185 мм');

INSERT INTO eshop.up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE)
VALUES (2, 10, 'нет');


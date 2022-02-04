use test;

#for users
CREATE TABLE item (
	ID int not null auto_increment,
	TITLE varchar(128),
	PRICE float,
	SHORT_DESC varchar(128),
	FULL_DESC varchar(1024),
	SPECS varchar(2048),

	PRIMARY KEY (ID)
);

#for content managers
CREATE TABLE spec_template (
	ID int not null auto_increment,
	NAME varchar(64),
	TEMPLATE varchar(2048),

	PRIMARY KEY (ID)
);

INSERT INTO spec_template (NAME, TEMPLATE)
VALUES ('Клавиатура', 'Внешний вид<n>Цвет<s>Подсветка<c>Подключение и интерфейсы<n>Тип подключения<s>Интерфейс подключения');
INSERT INTO spec_template (NAME, TEMPLATE)
VALUES ('Видеокарта', 'Спецификации видеопамяти<n>Объем видеопамяти<s>Тип памяти<c>Спецификации видеопроцессора<n>Техпроцесс<s>Штатная частота работы видеочипа');

SELECT * FROM item;

CREATE TABLE IF NOT EXISTS up_item_type
(
	ID int not null auto_increment,
	NAME varchar(500) not null,
	PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_spec_category
(
	ID   int          not null auto_increment,
	NAME varchar(500) not null,
	DISPLAY_ORDER int DEFAULT 0,
	PRIMARY KEY (ID)

);

CREATE TABLE IF NOT EXISTS up_spec_type
(
	ID               int          not null auto_increment,
	NAME             varchar(500) not null,
	SPEC_CATEGORY_ID int          not null,
	DISPLAY_ORDER int DEFAULT 0,
	TYPE varchar(20),
	PRIMARY KEY (ID),
	FOREIGN KEY FK_ST_SC (SPEC_CATEGORY_ID)
		REFERENCES up_spec_category (ID)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS up_item_spec
(
	ITEM_ID      int not null,
	SPEC_TYPE_ID int not null,
	PRIMARY KEY (ITEM_ID, SPEC_TYPE_ID),
	FOREIGN KEY FK_IS_ITEM (ITEM_ID)
		REFERENCES up_item (ID)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	FOREIGN KEY FK_IS_ST (SPEC_TYPE_ID)
		REFERENCES up_spec_type (ID)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS up_spec_template
(
    ITEM_TYPE_ID int not null,
    SPEC_TYPE_ID int not null,
    PRIMARY KEY (ITEM_TYPE_ID, SPEC_TYPE_ID),
    FOREIGN KEY FK_ST_IT (ITEM_TYPE_ID)
	    REFERENCES up_item_type (ID)
	    ON UPDATE RESTRICT
	    ON DELETE RESTRICT,
    FOREIGN KEY FK_ST_ST (SPEC_TYPE_ID)
	    REFERENCES up_spec_type (ID)
	    ON UPDATE RESTRICT
	    ON DELETE RESTRICT
)
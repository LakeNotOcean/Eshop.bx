CREATE TABLE IF NOT EXISTS up_role
(
	ID   int                 not null auto_increment,
	NAME varchar(100) UNIQUE not null,
	PRIMARY KEY (ID)
);
CREATE TABLE IF NOT EXISTS up_user
(
	ID       int                 not null auto_increment,
	LOGIN    varchar(100) UNIQUE not null,
	EMAIL    varchar(100) UNIQUE not null,
	PHONE    varchar(100) UNIQUE not null,
	PASSWORD varchar(100) UNIQUE not null,
	ROLE_ID  int                 not null,
	PRIMARY KEY (ID),
	FOREIGN KEY FK_U_R (ROLE_ID)
		REFERENCES up_role (ID)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

DROP TABLE up_roles;

INSERT INTO up_role (NAME) VALUES ('Admin'), ('User'),('Moderator');
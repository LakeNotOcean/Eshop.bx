CREATE TABLE IF NOT EXISTS up_role
(
	ID   int          not null auto_increment,
	NAME varchar(255) not null,
	PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_user
(
	ID       int          not null auto_increment,
	LOGIN    varchar(500) not null,
	EMAIL    varchar(255) not null,
	PHONE    varchar(255) not null,
	PASSWORD varchar(255) not null,
	ROLE_ID  int          not null,
	PRIMARY KEY (ID),
	FOREIGN KEY FK_U_R (ROLE_ID)
		REFERENCES up_role (ID)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

INSERT INTO up_role (NAME) VALUES ('Admin'),('Moderator'),('User');
DROP TABLE up_roles;
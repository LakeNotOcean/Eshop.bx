alter table up_user
	add ROLE_ID int null;

alter table up_user
	add EMAIL VARCHAR(255) null;

alter table up_user
	add PHONE VARCHAR(255) null;

alter table up_order
	drop column EMAIL;

alter table up_order
	drop column PHONE;

alter table up_order
	add USER_ID int null;

create table IF NOT EXISTS up_roles
(
	ID   int          null,
	NAME VARCHAR(255) null
);

create table IF NOT EXISTS up_review
(
	ID      int  null,
	USER_ID int  null,
	ITEM_ID int  null,
	SCORE   int  null,
	COMMENT TEXT null
);




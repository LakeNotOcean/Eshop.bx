alter table up_order
	drop column CUSTOMER_NAME;

alter table up_order
	add CUSTOMER_FIRST_NAME varchar(125) null;

alter table up_order
	add CUSTOMER_SECOND_NAME varchar(125) null;

alter table `up_order-item`
	add constraint `up_order-item_pk`
		primary key (ORDER_ID, ITEM_ID);

alter table `up_order-item`
	add constraint `up_order-item_up_order_ID_fk`
		foreign key (ORDER_ID) references up_order (ID);

alter table up_review
	add constraint up_review_pk
		primary key (ID);

alter table up_review
	add constraint up_review_pk_2
		unique (USER_ID, ITEM_ID);

alter table `up_user-favorite_item`
	add constraint `up_user-favorite_item_pk`
		primary key (USER_ID, FAVORITE_ITEM_ID);
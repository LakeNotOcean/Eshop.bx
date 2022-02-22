alter table `up_item-spec` drop foreign key `up_item-spec_ibfk_1`;

alter table `up_item-spec`
	add constraint `up_item-spec_ibfk_1`
		foreign key (ITEM_ID) references up_item (ID)
			on delete cascade;

alter table `up_order-item`
	add constraint `up_order-item_up_item_ID_fk`
		foreign key (ITEM_ID) references up_item (ID)
			on delete cascade;

alter table up_review
	add constraint up_review_up_item_ID_fk
		foreign key (ITEM_ID) references up_item (ID)
			on delete cascade;

alter table up_review
	add constraint up_review_up_user_ID_fk
		foreign key (USER_ID) references up_user (ID)
			on delete cascade;

alter table `up_user-favorite_item`
	add constraint `up_user-favorite_item_up_user_ID_fk`
		foreign key (USER_ID) references up_user (ID)
			on delete cascade;

alter table `up_user-favorite_item`
	add constraint `up_user-favorite_item_up_item_ID_fk`
		foreign key (FAVORITE_ITEM_ID) references up_item (ID)
			on delete cascade;
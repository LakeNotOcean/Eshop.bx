alter table up_review alter column ID drop default;

create unique index up_review_ID_uindex
	on up_review (ID);
alter table up_review modify ID int auto_increment;

alter table up_order
	add constraint up_order_up_user_ID_fk
		foreign key (USER_ID) references up_user (ID)
			on delete cascade;

alter table up_review
	add DATE_CREATE date not null;
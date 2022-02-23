alter table `up_order-item`
	add constraint `up_order-item_up_order_ID_fk`
		foreign key (ORDER_ID) references up_order (ID)
			on delete cascade;
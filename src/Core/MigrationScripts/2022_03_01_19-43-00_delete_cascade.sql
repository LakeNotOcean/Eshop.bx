alter table up_tag drop foreign key up_tag_up_item_type_ID_fk;

alter table up_tag
	add constraint up_tag_up_item_type_ID_fk
		foreign key (ITEM_TYPE_ID) references up_item_type (ID)
			on delete cascade;

alter table up_spec_template drop foreign key up_spec_template_ibfk_1;

alter table up_spec_template
	add constraint up_spec_template_ibfk_1
		foreign key (ITEM_TYPE_ID) references up_item_type (ID)
			on delete cascade;

alter table up_item
	add constraint up_item_up_item_type_ID_fk
		foreign key (ITEM_TYPE_ID) references up_item_type (ID)
			on delete cascade;
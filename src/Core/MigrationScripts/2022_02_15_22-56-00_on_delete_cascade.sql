alter table up_spec_type drop foreign key up_spec_type_ibfk_1;

alter table up_spec_type
	add constraint up_spec_type_ibfk_1
		foreign key (SPEC_CATEGORY_ID) references up_spec_category (ID)
			on delete cascade;

alter table up_spec_template drop foreign key up_spec_template_ibfk_2;

alter table up_spec_template
	add constraint up_spec_template_ibfk_2
		foreign key (SPEC_TYPE_ID) references up_spec_type (ID)
			on delete cascade;

alter table up_item_spec drop foreign key up_item_spec_ibfk_2;

alter table up_item_spec
	add constraint up_item_spec_ibfk_2
		foreign key (SPEC_TYPE_ID) references up_spec_type (ID)
			on delete cascade;


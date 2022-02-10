alter table up_tag modify TITLE varchar(150) null;

create unique index up_tag_TITLE_uindex
	on up_tag (TITLE);

alter table up_spec_type modify NAME varchar(150) not null;

alter table up_spec_type
	add constraint up_spec_type_pk
		unique (NAME, SPEC_CATEGORY_ID);

alter table up_spec_category modify NAME varchar(150) not null;

alter table up_spec_category
	add constraint up_spec_category_pk
		unique (NAME);

alter table up_item_type modify NAME varchar(150) not null;

alter table up_item_type
	add constraint up_item_type_pk
		unique (NAME);
drop index up_tag_TITLE_uindex on up_tag;

create unique index up_tag_TITLE_ITEM_TYPE_ID_uindex
	on up_tag (TITLE, ITEM_TYPE_ID);
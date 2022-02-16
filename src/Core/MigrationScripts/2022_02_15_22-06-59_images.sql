rename table up_image to up_original_image;

create table up_sized_image
(
	ID                int auto_increment,
	ORIGINAL_IMAGE_ID int          not null,
	PATH              varchar(255) not null,
	SIZE              varchar(15)  not null,
	constraint up_sized_image_pk
		primary key (ID),
	constraint up_sized_image_up_original_image_ID_fk
		foreign key (ORIGINAL_IMAGE_ID) references up_original_image (ID)
);

create unique index up_sized_image_ID_uindex
	on up_sized_image (ID);

create unique index up_sized_image_PATH_uindex
	on up_sized_image (PATH);


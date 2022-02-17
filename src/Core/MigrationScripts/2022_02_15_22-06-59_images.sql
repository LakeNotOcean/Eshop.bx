rename table up_image to up_original_image;

create table up_image_with_size
(
	ID                int auto_increment,
	ORIGINAL_IMAGE_ID int          not null,
	PATH              varchar(150) not null,
	SIZE              varchar(15)  not null,
	constraint up_sized_image_pk
		primary key (ID),
	constraint up_sized_image_up_original_image_ID_fk
		foreign key (ORIGINAL_IMAGE_ID) references up_original_image (ID)
);


create unique index up_sized_image_ID_uindex
	on up_image_with_size (ID);

create unique index up_sized_image_PATH_uindex
	on up_image_with_size (PATH);

alter table up_image_with_size
	drop foreign key up_sized_image_up_original_image_ID_fk;

alter table up_image_with_size
	add constraint up_sized_image_up_original_image_ID_fk
		foreign key (ORIGINAL_IMAGE_ID) references up_original_image (ID)
			on delete cascade;

alter table up_original_image
	drop foreign key up_image_up_item_ID_fk;

alter table up_original_image
	add constraint up_image_up_item_ID_fk
		foreign key (ITEM_ID) references up_item (ID)
			on delete cascade;



delete
from up_original_image
where ID > 0;

INSERT INTO `up_original_image` (`ID`, `PATH`, `ITEM_ID`, `IS_MAIN`)
VALUES (31, 'img/original/65106ac8d1a71c43364bacafb86b91fb.webp', 1, 1),
       (32, 'img/original/b82ab1ea240df9b7e0bfc7bb96f2bbd5.webp', 2, 1),
       (33, 'img/original/19db9203476897b10a5e67036a520d6e.webp', 3, 1),
       (34, 'img/original/32704ff474bf0c6406c910735afebb7c.webp', 4, 1),
       (35, 'img/original/dcc60b46eb87c1aabc236bfd25eb25ab.webp', 5, 1),
       (36, 'img/original/d5fa3d2dad1225c8eab34bcc3f20ddab.webp', 6, 1),
       (37, 'img/original/1ebaf13f683937dfa475e1b2a90ddae6.webp', 7, 1),
       (38, 'img/original/11464a694393ee987d4066dc6f1207fc.webp', 8, 1),
       (39, 'img/original/6c7385b72b0299b83981bb84c53c6d41.webp', 9, 1),
       (40, 'img/original/e85e6f7822abadea88d6e6387a84045f.webp', 10, 1),
       (41, 'img/original/74b02b288e07f789d0c6e5aea620a5bc.webp', 11, 1),
       (42, 'img/original/e8b3adbbaf2d7a590232e52190d9d36e.webp', 12, 1),
       (43, 'img/original/6bc62577ec3966e271936c435e6ce85c.webp', 13, 1),
       (44, 'img/original/de0224c14ff11babc6b6ece0add3fda3.webp', 14, 1),
       (45, 'img/original/0fc06df58ae6a91446cb6b7daabd5067.webp', 15, 1),
       (46, 'img/original/838533612da4f9d00354e02fdd007872.webp', 16, 1),
       (47, 'img/original/bebeae94fc0eaf1a9b706a06eaf4fc5f.webp', 17, 1),
       (48, 'img/original/9c0aac834a9b1de6d43b5aee74437a04.webp', 18, 1),
       (49, 'img/original/64bbb4fa481111fc4723062718066559.webp', 19, 1),
       (50, 'img/original/17278585f50157938bfbc6753b4ad796.webp', 20, 1),
       (51, 'img/original/732d02dcacc3dace5cab5c561e041884.webp', 21, 1),
       (52, 'img/original/1c9bca041f731babe2727f1abdeafce1.webp', 22, 1),
       (53, 'img/original/7a093544ca29b643365cc3aac4cadc55.webp', 23, 1),
       (54, 'img/original/a567541f0639e5653b17ee5e6dda9612.webp', 24, 1),
       (55, 'img/original/a6e4d40b3e4d25a328c2c930b14b35eb.webp', 25, 1),
       (56, 'img/original/d29075412de71a0a622f81d72d3d452f.webp', 26, 1),
       (57, 'img/original/2777e96d7cddf0fe532e9c6340a835bc.webp', 27, 1),
       (58, 'img/original/23d5dab278dead446cc5c8f7bd06a7ed.webp', 28, 1),
       (59, 'img/original/57744c3f9648130b03aa355a88536051.webp', 29, 1),
       (60, 'img/original/574370df9970593cab3e13858c652f28.webp', 30, 1);

INSERT INTO `up_image_with_size` (`ID`, `ORIGINAL_IMAGE_ID`, `PATH`, `SIZE`)
VALUES (1, 31, 'img/small/65106ac8d1a71c43364bacafb86b91fb.webp', 'small'),
       (2, 31, 'img/medium/65106ac8d1a71c43364bacafb86b91fb.webp', 'medium'),
       (3, 31, 'img/big/65106ac8d1a71c43364bacafb86b91fb.webp', 'big'),
       (4, 32, 'img/small/b82ab1ea240df9b7e0bfc7bb96f2bbd5.webp', 'small'),
       (5, 32, 'img/medium/b82ab1ea240df9b7e0bfc7bb96f2bbd5.webp', 'medium'),
       (6, 32, 'img/big/b82ab1ea240df9b7e0bfc7bb96f2bbd5.webp', 'big'),
       (7, 33, 'img/small/19db9203476897b10a5e67036a520d6e.webp', 'small'),
       (8, 33, 'img/medium/19db9203476897b10a5e67036a520d6e.webp', 'medium'),
       (9, 33, 'img/big/19db9203476897b10a5e67036a520d6e.webp', 'big'),
       (10, 34, 'img/small/32704ff474bf0c6406c910735afebb7c.webp', 'small'),
       (11, 34, 'img/medium/32704ff474bf0c6406c910735afebb7c.webp', 'medium'),
       (12, 34, 'img/big/32704ff474bf0c6406c910735afebb7c.webp', 'big'),
       (13, 35, 'img/small/dcc60b46eb87c1aabc236bfd25eb25ab.webp', 'small'),
       (14, 35, 'img/medium/dcc60b46eb87c1aabc236bfd25eb25ab.webp', 'medium'),
       (15, 35, 'img/big/dcc60b46eb87c1aabc236bfd25eb25ab.webp', 'big'),
       (16, 36, 'img/small/d5fa3d2dad1225c8eab34bcc3f20ddab.webp', 'small'),
       (17, 36, 'img/medium/d5fa3d2dad1225c8eab34bcc3f20ddab.webp', 'medium'),
       (18, 36, 'img/big/d5fa3d2dad1225c8eab34bcc3f20ddab.webp', 'big'),
       (19, 37, 'img/small/1ebaf13f683937dfa475e1b2a90ddae6.webp', 'small'),
       (20, 37, 'img/medium/1ebaf13f683937dfa475e1b2a90ddae6.webp', 'medium'),
       (21, 37, 'img/big/1ebaf13f683937dfa475e1b2a90ddae6.webp', 'big'),
       (22, 38, 'img/small/11464a694393ee987d4066dc6f1207fc.webp', 'small'),
       (23, 38, 'img/medium/11464a694393ee987d4066dc6f1207fc.webp', 'medium'),
       (24, 38, 'img/big/11464a694393ee987d4066dc6f1207fc.webp', 'big'),
       (25, 39, 'img/small/6c7385b72b0299b83981bb84c53c6d41.webp', 'small'),
       (26, 39, 'img/medium/6c7385b72b0299b83981bb84c53c6d41.webp', 'medium'),
       (27, 39, 'img/big/6c7385b72b0299b83981bb84c53c6d41.webp', 'big'),
       (28, 40, 'img/small/e85e6f7822abadea88d6e6387a84045f.webp', 'small'),
       (29, 40, 'img/medium/e85e6f7822abadea88d6e6387a84045f.webp', 'medium'),
       (30, 40, 'img/big/e85e6f7822abadea88d6e6387a84045f.webp', 'big'),
       (31, 41, 'img/small/74b02b288e07f789d0c6e5aea620a5bc.webp', 'small'),
       (32, 41, 'img/medium/74b02b288e07f789d0c6e5aea620a5bc.webp', 'medium'),
       (33, 41, 'img/big/74b02b288e07f789d0c6e5aea620a5bc.webp', 'big'),
       (34, 42, 'img/small/e8b3adbbaf2d7a590232e52190d9d36e.webp', 'small'),
       (35, 42, 'img/medium/e8b3adbbaf2d7a590232e52190d9d36e.webp', 'medium'),
       (36, 42, 'img/big/e8b3adbbaf2d7a590232e52190d9d36e.webp', 'big'),
       (37, 43, 'img/small/6bc62577ec3966e271936c435e6ce85c.webp', 'small'),
       (38, 43, 'img/medium/6bc62577ec3966e271936c435e6ce85c.webp', 'medium'),
       (39, 43, 'img/big/6bc62577ec3966e271936c435e6ce85c.webp', 'big'),
       (40, 44, 'img/small/de0224c14ff11babc6b6ece0add3fda3.webp', 'small'),
       (41, 44, 'img/medium/de0224c14ff11babc6b6ece0add3fda3.webp', 'medium'),
       (42, 44, 'img/big/de0224c14ff11babc6b6ece0add3fda3.webp', 'big'),
       (43, 45, 'img/small/0fc06df58ae6a91446cb6b7daabd5067.webp', 'small'),
       (44, 45, 'img/medium/0fc06df58ae6a91446cb6b7daabd5067.webp', 'medium'),
       (45, 45, 'img/big/0fc06df58ae6a91446cb6b7daabd5067.webp', 'big'),
       (46, 46, 'img/small/838533612da4f9d00354e02fdd007872.webp', 'small'),
       (47, 46, 'img/medium/838533612da4f9d00354e02fdd007872.webp', 'medium'),
       (48, 46, 'img/big/838533612da4f9d00354e02fdd007872.webp', 'big'),
       (49, 47, 'img/small/bebeae94fc0eaf1a9b706a06eaf4fc5f.webp', 'small'),
       (50, 47, 'img/medium/bebeae94fc0eaf1a9b706a06eaf4fc5f.webp', 'medium'),
       (51, 47, 'img/big/bebeae94fc0eaf1a9b706a06eaf4fc5f.webp', 'big'),
       (52, 48, 'img/small/9c0aac834a9b1de6d43b5aee74437a04.webp', 'small'),
       (53, 48, 'img/medium/9c0aac834a9b1de6d43b5aee74437a04.webp', 'medium'),
       (54, 48, 'img/big/9c0aac834a9b1de6d43b5aee74437a04.webp', 'big'),
       (55, 49, 'img/small/64bbb4fa481111fc4723062718066559.webp', 'small'),
       (56, 49, 'img/medium/64bbb4fa481111fc4723062718066559.webp', 'medium'),
       (57, 49, 'img/big/64bbb4fa481111fc4723062718066559.webp', 'big'),
       (58, 50, 'img/small/17278585f50157938bfbc6753b4ad796.webp', 'small'),
       (59, 50, 'img/medium/17278585f50157938bfbc6753b4ad796.webp', 'medium'),
       (60, 50, 'img/big/17278585f50157938bfbc6753b4ad796.webp', 'big'),
       (61, 51, 'img/small/732d02dcacc3dace5cab5c561e041884.webp', 'small'),
       (62, 51, 'img/medium/732d02dcacc3dace5cab5c561e041884.webp', 'medium'),
       (63, 51, 'img/big/732d02dcacc3dace5cab5c561e041884.webp', 'big'),
       (64, 52, 'img/small/1c9bca041f731babe2727f1abdeafce1.webp', 'small'),
       (65, 52, 'img/medium/1c9bca041f731babe2727f1abdeafce1.webp', 'medium'),
       (66, 52, 'img/big/1c9bca041f731babe2727f1abdeafce1.webp', 'big'),
       (67, 53, 'img/small/7a093544ca29b643365cc3aac4cadc55.webp', 'small'),
       (68, 53, 'img/medium/7a093544ca29b643365cc3aac4cadc55.webp', 'medium'),
       (69, 53, 'img/big/7a093544ca29b643365cc3aac4cadc55.webp', 'big'),
       (70, 54, 'img/small/a567541f0639e5653b17ee5e6dda9612.webp', 'small'),
       (71, 54, 'img/medium/a567541f0639e5653b17ee5e6dda9612.webp', 'medium'),
       (72, 54, 'img/big/a567541f0639e5653b17ee5e6dda9612.webp', 'big'),
       (73, 55, 'img/small/a6e4d40b3e4d25a328c2c930b14b35eb.webp', 'small'),
       (74, 55, 'img/medium/a6e4d40b3e4d25a328c2c930b14b35eb.webp', 'medium'),
       (75, 55, 'img/big/a6e4d40b3e4d25a328c2c930b14b35eb.webp', 'big'),
       (76, 56, 'img/small/d29075412de71a0a622f81d72d3d452f.webp', 'small'),
       (77, 56, 'img/medium/d29075412de71a0a622f81d72d3d452f.webp', 'medium'),
       (78, 56, 'img/big/d29075412de71a0a622f81d72d3d452f.webp', 'big'),
       (79, 57, 'img/small/2777e96d7cddf0fe532e9c6340a835bc.webp', 'small'),
       (80, 57, 'img/medium/2777e96d7cddf0fe532e9c6340a835bc.webp', 'medium'),
       (81, 57, 'img/big/2777e96d7cddf0fe532e9c6340a835bc.webp', 'big'),
       (82, 58, 'img/small/23d5dab278dead446cc5c8f7bd06a7ed.webp', 'small'),
       (83, 58, 'img/medium/23d5dab278dead446cc5c8f7bd06a7ed.webp', 'medium'),
       (84, 58, 'img/big/23d5dab278dead446cc5c8f7bd06a7ed.webp', 'big'),
       (85, 59, 'img/small/57744c3f9648130b03aa355a88536051.webp', 'small'),
       (86, 59, 'img/medium/57744c3f9648130b03aa355a88536051.webp', 'medium'),
       (87, 59, 'img/big/57744c3f9648130b03aa355a88536051.webp', 'big'),
       (88, 60, 'img/small/574370df9970593cab3e13858c652f28.webp', 'small'),
       (89, 60, 'img/medium/574370df9970593cab3e13858c652f28.webp', 'medium'),
       (90, 60, 'img/big/574370df9970593cab3e13858c652f28.webp', 'big');
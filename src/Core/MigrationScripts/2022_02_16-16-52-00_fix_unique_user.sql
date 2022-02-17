DROP TABLE IF EXISTS up_user;
CREATE TABLE up_user (
	                       `ID` int(11) AUTO_INCREMENT,
	                       `LOGIN` varchar(100) UNIQUE NOT NULL ,
	                       `PASSWORD` varchar(100)  NOT NULL ,
	                       `ROLE_ID` int(11) NOT NULL,
	                       `EMAIL` varchar(100) UNIQUE DEFAULT NULL,
	                       `PHONE` varchar(100) UNIQUE DEFAULT NULL,
	                       `FIRST_NAME` varchar(100) DEFAULT NULL,
	                       `SECOND_NAME` varchar(100) DEFAULT NULL,
	                       PRIMARY KEY (ID),
	                       FOREIGN KEY FK_UU_UR (ROLE_ID)
                            REFERENCES up_role(ID)
                            ON UPDATE RESTRICT
                            ON DELETE RESTRICT
);


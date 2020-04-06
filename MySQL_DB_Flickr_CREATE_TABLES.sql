use Flickr;

SET sql_mode = ALLOW_INVALID_DATES;

CREATE TABLE `blphotos20200330` 
  ( 
     `photoid`              CHAR(11) NOT NULL DEFAULT '', 
     `secret`               CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `server`               MEDIUMINT DEFAULT '0', 
     `farm`                 TINYINT DEFAULT '0', 
     `title`                CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT '0', 
     `ispublic`             TINYINT(1) DEFAULT '1', 
     `license`              TINYINT(1) DEFAULT '0', 
     `o_width`              SMALLINT DEFAULT '0', 
     `o_height`             SMALLINT DEFAULT '0', 
     `dateupload`           CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT '0', 
     `lastupdate`           CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `datetaken`            TIMESTAMP NULL DEFAULT NULL, 
     `datetakengranularity` INT DEFAULT NULL, 
     `datetakenunknown`     INT DEFAULT NULL, 
     `ownername`            CHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `iconserver`           CHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `iconfarm`             CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `views`                MEDIUMINT DEFAULT NULL, 
     `tags`                 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci, 
     `machine_tags`         TEXT CHARACTER SET utf8 COLLATE utf8_general_ci, 
     `originalsecret`       CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT 
     NULL, 
     `originalformat`       CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT 
     NULL, 
     `latitude`             CHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `longitude`            CHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `accuracy`             CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `context`              CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `media`                CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `media_status`         CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `url_sq`               VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_sq`            SMALLINT DEFAULT NULL, 
     `width_sq`             SMALLINT DEFAULT NULL, 
     `url_t`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_t`             SMALLINT DEFAULT NULL, 
     `width_t`              SMALLINT DEFAULT NULL, 
     `url_s`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_s`             SMALLINT DEFAULT NULL, 
     `width_s`              SMALLINT DEFAULT NULL, 
     `url_q`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_q`             SMALLINT DEFAULT NULL, 
     `width_q`              SMALLINT DEFAULT NULL, 
     `url_m`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_m`             SMALLINT DEFAULT NULL, 
     `width_m`              SMALLINT DEFAULT NULL, 
     `url_n`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_n`             SMALLINT DEFAULT NULL, 
     `width_n`              SMALLINT DEFAULT NULL, 
     `url_z`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_z`             SMALLINT DEFAULT NULL, 
     `width_z`              SMALLINT DEFAULT NULL, 
     `url_c`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_c`             SMALLINT DEFAULT NULL, 
     `width_c`              SMALLINT DEFAULT NULL, 
     `url_l`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_l`             SMALLINT DEFAULT NULL, 
     `width_l`              SMALLINT DEFAULT NULL, 
     `url_o`                VARCHAR(100) CHARACTER SET utf8 COLLATE 
     utf8_general_ci DEFAULT NULL, 
     `height_o`             SMALLINT DEFAULT NULL, 
     `width_o`              SMALLINT DEFAULT NULL, 
     `pathalias`            CHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci 
     DEFAULT NULL, 
     `description`          TEXT CHARACTER SET utf8 COLLATE utf8_general_ci, 
     PRIMARY KEY (`photoid`) 
  ) 
engine=innodb 
DEFAULT charset=utf8; 

CREATE TABLE `blphotosnewdata` 
  ( 
     `photoid`     CHAR(11) NOT NULL DEFAULT '', 
     `title`       CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 
     '0', 
     `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci, 
     PRIMARY KEY (`photoid`) 
  ) 
engine=innodb 
DEFAULT charset=utf8; 

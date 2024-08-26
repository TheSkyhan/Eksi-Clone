CREATE DATABASE `kadimsozluk` DEFAULT CHARACTER SET utf8 ;
CREATE TABLE `tbl_badi` (
  `badi_yazar` tinytext NOT NULL,
  `badi_kimle` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_duyuru` (
  `duyuru_kimden` varchar(255) NOT NULL,
  `duyuru_icerik` varchar(1000) NOT NULL,
  `duyuru_zaman` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_entries` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_sirano` int(6) NOT NULL,
  `entry_baslik` varchar(255) NOT NULL,
  `entry_text` text NOT NULL,
  `entry_yazar` varchar(255) NOT NULL,
  `entry_giristarihi` datetime NOT NULL,
  `entry_sonedittarihi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entry_iyi` smallint(5) unsigned NOT NULL,
  `entry_kotu` smallint(5) unsigned NOT NULL,
  `entry_eksi` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `basliklar` (`entry_baslik`),
  KEY `entry_text` (`entry_text`(5)),
  KEY `giristarihi` (`entry_giristarihi`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_ispiyon` (
  `isp_ispid` int(5) NOT NULL AUTO_INCREMENT,
  `isp_entryid` int(10) NOT NULL,
  `isp_kim` varchar(255) NOT NULL,
  `isp_neden` varchar(1000) NOT NULL,
  PRIMARY KEY (`isp_ispid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_kenar` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_baslik` varchar(255) NOT NULL,
  `entry_text` text NOT NULL,
  `entry_yazar` varchar(255) NOT NULL,
  `entry_giristarihi` datetime NOT NULL,
  `entry_sonedittarihi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `tbl_mesaj` (
  `msg_msgid` int(6) NOT NULL AUTO_INCREMENT,
  `msg_kimden` varchar(255) NOT NULL,
  `msg_kime` varchar(255) NOT NULL,
  `msg_icerik` varchar(1000) NOT NULL,
  `msg_zaman` datetime NOT NULL,
  PRIMARY KEY (`msg_msgid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_ukte` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_baslik` varchar(255) NOT NULL,
  `entry_text` text NOT NULL,
  `entry_yazar` varchar(255) NOT NULL,
  `entry_giristarihi` datetime NOT NULL,
  `entry_sonedittarihi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`,`entry_baslik`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_users` (
  `user_nick` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pwd` varchar(32) NOT NULL,
  `user_dogumtarihi` date NOT NULL,
  `user_kayittarihi` date NOT NULL,
  `user_cinsiyet` enum('e','k','d') NOT NULL,
  `user_sonmesajokuma` datetime NOT NULL,
  `user_mod` enum('0','1') NOT NULL,
  PRIMARY KEY (`user_email`),
  UNIQUE KEY `user_nick` (`user_nick`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



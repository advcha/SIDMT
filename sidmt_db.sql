/*
SQLyog Community v9.63 
MySQL - 5.5.32-log : Database - sidmt
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sidmt` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sidmt`;

/*Table structure for table `catudaya` */

DROP TABLE IF EXISTS `catudaya`;

CREATE TABLE `catudaya` (
  `idcatudaya` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idtower` int(11) DEFAULT NULL,
  `kodecatudaya` tinyint(4) DEFAULT NULL,
  `catudaya` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idcatudaya`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

/*Table structure for table `izin` */

DROP TABLE IF EXISTS `izin`;

CREATE TABLE `izin` (
  `idizin` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `no_izin_prinsip` varchar(50) DEFAULT NULL,
  `tgl_izin_prinsip` date DEFAULT NULL,
  `tgl_hbs_izin_prinsip` date DEFAULT NULL,
  `no_izin_ho` varchar(50) DEFAULT NULL,
  `tgl_izin_ho` date DEFAULT NULL,
  `tgl_hbs_izin_ho` date DEFAULT NULL,
  `no_imb` varchar(50) DEFAULT NULL,
  `tgl_imb` date DEFAULT NULL,
  `tgl_hbs_imb` date DEFAULT NULL,
  `idizinlama` int(11) NOT NULL DEFAULT '0',
  `file_imb` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idizin`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

/*Table structure for table `kecamatan` */

DROP TABLE IF EXISTS `kecamatan`;

CREATE TABLE `kecamatan` (
  `idkec` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `kecamatan` varchar(25) DEFAULT NULL,
  `path_warna` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idkec`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `log` */

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `idlog` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logtime` datetime NOT NULL,
  `iduser` int(10) unsigned NOT NULL,
  `jenis` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `idakses` int(10) unsigned NOT NULL,
  `modulakses` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `keteranganakses` varchar(10000) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idlog`),
  UNIQUE KEY `idlog` (`idlog`),
  KEY `iduser` (`iduser`),
  KEY `idakses` (`idakses`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

/*Table structure for table `nagari` */

DROP TABLE IF EXISTS `nagari`;

CREATE TABLE `nagari` (
  `idnagari` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nagari` varchar(50) DEFAULT NULL,
  `idkec` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idnagari`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;

/*Table structure for table `njop` */

DROP TABLE IF EXISTS `njop`;

CREATE TABLE `njop` (
  `idnjop` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `njop_tanah` float DEFAULT '0',
  `njop_menara` float DEFAULT '0',
  `njop_total` float DEFAULT '0',
  `retribusi` float DEFAULT '0',
  PRIMARY KEY (`idnjop`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `operator` */

DROP TABLE IF EXISTS `operator`;

CREATE TABLE `operator` (
  `idoperator` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `perwakilan` varchar(100) DEFAULT NULL,
  `alamat_perwakilan` varchar(250) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `pemilik` varchar(100) NOT NULL,
  `telp_pemilik` varchar(50) NOT NULL,
  PRIMARY KEY (`idoperator`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Table structure for table `opgabung` */

DROP TABLE IF EXISTS `opgabung`;

CREATE TABLE `opgabung` (
  `idopgabung` int(11) NOT NULL AUTO_INCREMENT,
  `idtower` int(11) NOT NULL,
  `idoperator` int(11) NOT NULL,
  PRIMARY KEY (`idopgabung`)
) ENGINE=MyISAM AUTO_INCREMENT=733 DEFAULT CHARSET=latin1;

/*Table structure for table `pemilik` */

DROP TABLE IF EXISTS `pemilik`;

CREATE TABLE `pemilik` (
  `idpemilik` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `perwakilan` varchar(100) DEFAULT NULL,
  `alamat_perwakilan` varchar(250) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `pemilik` varchar(100) DEFAULT NULL,
  `telp_pemilik` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idpemilik`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `idsession` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `logintime` datetime NOT NULL,
  `logouttime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status_session` tinyint(4) NOT NULL,
  `ipaddress` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `hostname` varchar(25) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idsession`),
  UNIQUE KEY `idsession` (`idsession`),
  KEY `iduser` (`iduser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

/*Table structure for table `tb_student` */

DROP TABLE IF EXISTS `tb_student`;

CREATE TABLE `tb_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` text,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nim` (`nim`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `tower` */

DROP TABLE IF EXISTS `tower`;

CREATE TABLE `tower` (
  `idtower` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idizin` int(11) DEFAULT NULL,
  `idnjop` int(11) DEFAULT NULL,
  `lokasi` tinytext,
  `lokasi_sppt_pbb` tinytext,
  `kode_lokasi` varchar(10) DEFAULT NULL,
  `idkec` tinyint(4) DEFAULT NULL,
  `idnagari` tinyint(4) DEFAULT NULL,
  `tinggi` float DEFAULT NULL,
  `elevasi` float DEFAULT NULL,
  `luas_tanah` float DEFAULT NULL,
  `rab_tower` decimal(10,0) DEFAULT NULL,
  `koord_b` decimal(10,4) DEFAULT NULL,
  `koord_l` decimal(10,4) DEFAULT NULL,
  `idoperator` tinyint(4) DEFAULT NULL,
  `idpemilik` int(11) NOT NULL,
  `pemilik_tower` varchar(50) DEFAULT NULL,
  `operator_gabung` varchar(100) DEFAULT NULL,
  `pemilik_tanah` varchar(150) DEFAULT NULL,
  `status_tanah` tinyint(4) DEFAULT NULL,
  `lama_sewa` varchar(50) DEFAULT NULL,
  `akhir_kontrak` date DEFAULT NULL,
  `type_lokasi` tinyint(4) DEFAULT NULL,
  `jml_pengguna` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idtower`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `iduser` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(50) DEFAULT NULL,
  `user` varchar(20) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  `iduserlevel` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `useraccess` */

DROP TABLE IF EXISTS `useraccess`;

CREATE TABLE `useraccess` (
  `iduseraccess` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `iduserlevel` tinyint(3) DEFAULT NULL,
  `adddata` tinyint(3) DEFAULT NULL,
  `editdata` tinyint(3) DEFAULT NULL,
  `deldata` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`iduseraccess`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `userlevel` */

DROP TABLE IF EXISTS `userlevel`;

CREATE TABLE `userlevel` (
  `iduserlevel` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `userlevel` varchar(25) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`iduserlevel`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

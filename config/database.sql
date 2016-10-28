-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- --------------------------------------------------------


-- 
-- Table `tl_distributeur`
-- 

CREATE TABLE `tl_marker` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `title` varchar(64) NOT NULL default '',
  `alias` varchar(128) NOT NULL default '',
  `adresse1` varchar(255) NOT NULL default '',
  `adresse2` varchar(255) NOT NULL default '',
  `codepostal` varchar(32) NOT NULL default '',
  `ville` varchar(255) NOT NULL default '',
  `center` varchar(64) NOT NULL default '',
  `lat` varchar(64) NOT NULL default ''
  `lng` varchar(64) NOT NULL default ''
  `geocoderAddress` varchar(255) NOT NULL default '',
  `geocoderCountry` varchar(2) NOT NULL default '1',
  `published` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- 
-- Table `tl_distributeur_type`
-- 
CREATE TABLE `tl_marker_type` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(128) NOT NULL default '',
  `center` varchar(64) NOT NULL default '',  
  `published` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE FUNCTION SPLIT_STR(
  x VARCHAR(255),
  delim VARCHAR(12),
  pos INT
)
RETURNS VARCHAR(255)
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
       LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
       delim, '');

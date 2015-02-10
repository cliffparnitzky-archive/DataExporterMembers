-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `dataExporterMembersFields` blob NULL,
  `dataExporterMembersGroups` blob NULL
  `gender` varchar(10) NULL,  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
<?php

/**
 * Contao Open Source CMS
 *
 * Facebook albums via content element
 * 
 * @copyright inspiredminds 2015
 * @package   facebook_gallery
 * @link      http://www.inspiredminds.at
 * @author    Fritz Michael Gschwantner <fmg@inspiredminds.at>
 * @license   LGPL-3.0+
 */


$GLOBALS['TL_DCA']['tl_content']['palettes']['facebookGallery'] = '{type_legend},type,headline;{facebook_legend},fbAlbumId,fbAlbumTitle,fbAlbumCaption,fbAlbumSort,fbAlbumTimeout;{image_legend},size,imagemargin,perRow,fullsize,perPage,numberOfItems;{template_legend:hide},galleryTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['fbAlbumId'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['fbAlbumId'],
	'exclude'   => true,
	'inputType' => 'text',
	'eval'      => array('tl_class' => 'long','maxlength'=>255,'decodeEntities'=>true),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fbAlbumTitle'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fbAlbumTitle'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true,'tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fbAlbumCaption'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fbAlbumCaption'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true,'tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fbAlbumSort'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('fbAlbumSort_id_asc','fbAlbumSort_id_desc','fbAlbumSort_time_asc', 'fbAlbumSort_time_desc'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
	'eval'                    => array('includeBlankOption'=>true,'tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fbAlbumTimeout'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fbAlbumTimeout'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'natural','tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NULL",
);

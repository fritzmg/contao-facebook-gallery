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
 * @license   GPL-2.0
 */


$GLOBALS['TL_DCA']['tl_content']['palettes']['facebookGallery'] = '{type_legend},type,headline;{facebook_legend},fbAlbumId;{image_legend},size,imagemargin,perRow,fullsize,perPage,numberOfItems;{template_legend:hide},galleryTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['fields']['fbAlbumId'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['fbAlbumId'],
	'exclude'   => true,
	'inputType' => 'text',
	'eval'      => array('tl_class' => 'long','maxlength'=>255,'decodeEntities'=>true),
	'sql'       => "varchar(255) NOT NULL default ''"
);

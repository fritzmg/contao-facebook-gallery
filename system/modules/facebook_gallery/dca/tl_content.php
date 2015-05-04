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

$GLOBALS['TL_DCA']['tl_content']['fields']['perPage']['load_callback'][] = array('tl_content_facebook_gallery','ppLoadCallback');

$GLOBALS['TL_DCA']['tl_content']['fields']['fbAlbumId'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['fbAlbumId'],
	'exclude'   => true,
	'inputType' => 'text',
	'eval'      => array('tl_class' => 'long','maxlength'=>255),
	'sql'       => "varchar(255) NOT NULL default ''"
);


class tl_content_facebook_gallery extends Backend
{
	public function ppLoadCallback( $varValue, DataContainer $dc )
	{
		// we can't fetch more than 100 images for one page
		if( ( $varValue <= 0 || $varValue > 100 ) && $dc->activeRecord->type == 'facebookGallery' )
			return 100;

		return $varValue;
	}
}

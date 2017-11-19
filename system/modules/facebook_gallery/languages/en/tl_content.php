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


$GLOBALS['TL_LANG']['tl_content']['facebook_legend'] = 'Facebook album';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumId'] = array('Album URL or Facebook ID','URL for the public Facebook album or just the album\'s Facebook ID.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumTitle'] = array('Album name as headline','The name of the album will be used as the headline automatically.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumCaption'] = array('Show image captions','The image\'s text from Facebook will be used as the image caption.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort'] = array('Sorting', 'Sorting of the images.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_id_asc'] = 'ID (ascending)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_id_desc'] = 'ID (descending)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_time_asc'] = 'Time (ascending)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_time_desc'] = 'Time (descending)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumTimeout'] = array('Cache timeout', 'Individual cache timeout in seconds. Cache will be circumvented when set to 0. The global cache timeout applies, if no value is present.');

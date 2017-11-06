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


/**
 * Content elements
 */
$GLOBALS['TL_CTE']['media']['facebookGallery'] = 'ContentFacebookGallery';


/**
 * Maintenance
 */
$GLOBALS['TL_PURGE']['folders']['facebook'] = array
(
	'affected' => array('system/cache/facebook'),
	'callback' => array('PurgeFacebookCache','purge')
);

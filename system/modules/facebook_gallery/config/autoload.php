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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Contao\ContentFacebookGallery' => 'system/modules/facebook_gallery/elements/ContentFacebookGallery.php',
	'Contao\PurgeFacebookCache'     => 'system/modules/facebook_gallery/classes/PurgeFacebookCache.php'
));

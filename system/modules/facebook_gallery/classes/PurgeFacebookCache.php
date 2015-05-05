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


namespace Contao;


class PurgeFacebookCache extends \Backend implements \executable
{

	/**
	 * Return true if the module is active
	 *
	 * @return boolean
	 */
	public function isActive()
	{
		return false;
	}


	/**
	 * Generate the module
	 *
	 * @return string
	 */
	public function run()
	{
		
	}


	/**
	 * Purge the twig cache directory
	 */
	public function purge()
	{
		$this->import('Files');

		$total = 0;
		$folder = 'system/cache/facebook';

		// only check existing folders
		if (is_dir(TL_ROOT . '/' . $folder))
		{
			// recursively scan all subfolders
			$objFiles = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator(
					TL_ROOT . '/' . $folder,
					\FilesystemIterator::UNIX_PATHS|\FilesystemIterator::FOLLOW_SYMLINKS|\FilesystemIterator::SKIP_DOTS
				)
			);

			// delete
			foreach ($objFiles as $objFile)
			{
				$this->Files->delete( substr( $objFile->getRealPath(), strlen(TL_ROOT) + 1 ) );
				++$total;
			}
		}

		// Add log entry
		$this->log('Purged facebook cache directory', __METHOD__, TL_GENERAL);

		return $total;
	}

}

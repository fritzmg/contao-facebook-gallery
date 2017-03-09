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


/**
 * Front end content element "facebook gallery".
 *
 * @author Fritz Michael Gschwantner <https://github.com/fritzmg>
 */
class ContentFacebookGallery extends \ContentElement
{

	/**
	 * Album ID
	 * @var string
	 */
	protected $strAlbumId = '';


	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_gallery';


	/**
	 * The Facebook data including album name and images.
	 * @var stdClass
	 */
	protected $objAlbumData = null;


	/**
	 * Image cache file path
	 * @var string
	 */
	protected $strCacheFile = '';


	/**
	 * Return if there are no files
	 * @return string
	 */
	public function generate()
	{
		// check if fbAlbumId is already numeric
		if( is_numeric( $this->fbAlbumId ) )
		{
			$this->strAlbumId = $this->fbAlbumId;
		}
		// otherwise extract from url
		elseif( preg_match('/https{0,1}:\/\/.*facebook.com\/.*a\.([0-9]*)\..*/i', $this->fbAlbumId, $matches) )
		{
			$this->strAlbumId = $matches[1];
		}
		elseif( preg_match('/album_id=([0-9]+)/i', $this->fbAlbumId, $matches) )
		{
			$this->strAlbumId = $matches[1];
		}

		// set type to gallery
		$this->type = 'gallery';

		// add CSS class
		$arrClasses = $this->cssID[1] ? explode( ' ', $this->cssID[1] ) : array();
		$arrClasses[] = 'facebook';
		$this->cssID = array( $this->cssID[0], implode( ' ', $arrClasses ) );

		// set the path to the cache file
		$this->strCacheFile = 'system/cache/facebook/album_' . $this->strAlbumId . '.json';

		// add headline from Facebook
		if( !$this->headline && $this->fbAlbumTitle )
		{
			// get the album data
			$objAlbumData = $this->getAlbumData();

			// check if name is present
			if( isset( $objAlbumData->name ) )
			{
				$this->headline = $objAlbumData->name;
			}
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		// check if we have an album id
		if( !$this->strAlbumId )
			return;

		// get the album data
		$objAlbumData = $this->getAlbumData();

		// get the images
		$images = $objAlbumData->images;

		// if there are no images, do nothing
		if( count( $images ) == 0 )
		{
			return;
		}

		// Limit the total number of items (see #2652)
		if ($this->numberOfItems > 0)
		{
			$images = array_slice($images, 0, $this->numberOfItems);
		}

		$offset = 0;
		$total = count($images);
		$limit = $total;

		// Pagination
		if ($this->perPage > 0)
		{
			// Get the current page
			$id = 'page_g' . $this->id;
			$page = (\Input::get($id) !== null) ? \Input::get($id) : 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				/** @var \PageError404 $objHandler */
				$objHandler = new $GLOBALS['TL_PTY']['error_404']();
				$objHandler->generate($objPage->id);
			}

			// Set limit and offset
			$offset = ($page - 1) * $this->perPage;
			$limit = min($this->perPage + $offset, $total);

			$objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$rowcount = 0;
		$colwidth = floor(100/$this->perRow);
		$intMaxWidth = (TL_MODE == 'BE') ? floor((640 / $this->perRow)) : floor((\Config::get('maxImageWidth') / $this->perRow));
		$strLightboxId = 'lightbox[lb' . $this->id . ']';
		$body = array();

		// Rows
		for ($i=$offset; $i<$limit; $i=($i+$this->perRow))
		{
			$class_tr = '';

			if ($rowcount == 0)
			{
				$class_tr .= ' row_first';
			}

			if (($i + $this->perRow) >= $limit)
			{
				$class_tr .= ' row_last';
			}

			$class_eo = (($rowcount % 2) == 0) ? ' even' : ' odd';

			// Columns
			for ($j=0; $j<$this->perRow; ++$j)
			{
				$class_td = '';

				if ($j == 0)
				{
					$class_td .= ' col_first';
				}

				if ($j == ($this->perRow - 1))
				{
					$class_td .= ' col_last';
				}

				$objCell = new \stdClass();
				$key = 'row_' . $rowcount . $class_tr . $class_eo;

				// Empty cell
				if (!is_object($images[($i+$j)]) || ($j+$i) >= $limit)
				{
					$objCell->colWidth = $colwidth . '%';
					$objCell->class = 'col_'.$j . $class_td;
				}
				else
				{
					// Add column width and class
					$objCell->colWidth = $colwidth . '%';
					$objCell->class = 'col_'.$j . $class_td;

					// get the image object
					$objImage = $images[($i+$j)];

					// process image and set parameters for gallery
					$img = $this->processImage( $objImage, $intMaxWidth );
					$objCell->addImage = '1';
					$objCell->margin = static::generateMargin( deserialize( $this->imagemargin ) );
					$objCell->href = $img['href'];
					$objCell->src = $img['src'];
					$objCell->imgSize = ' width="'.$img['width'].'" height="'.$img['height'].'"';

					// add caption
					if( $this->fbAlbumCaption )
					{
						$objCell->caption = $objImage->name;
					}

					// add Facebook data to cell
					$objCell->fbData = $objImage;
					
					if( version_compare( VERSION, '3.4', '>=' ) )
						$objCell->picture = array('img' => $img);

					if( $this->fullsize )
						$objCell->attributes = ($objPage->outputFormat == 'xhtml') ? ' rel="' . $strLightboxId . '"' : ' data-lightbox="' . substr($strLightboxId, 9, -1) . '"';
				}

				$body[$key][$j] = $objCell;
			}

			++$rowcount;
		}

		$strTemplate = 'gallery_default';

		// Use a custom template
		if (TL_MODE == 'FE' && $this->galleryTpl != '')
		{
			$strTemplate = $this->galleryTpl;
		}

		/** @var \FrontendTemplate|object $objTemplate */
		$objTemplate = new \FrontendTemplate($strTemplate);
		$objTemplate->setData($this->arrData);

		$objTemplate->body = $body;
		$objTemplate->headline = $this->headline; // see #1603

		$this->Template->images = $objTemplate->parse();
	}


	/**
	 * Returns an App access token parameter ("access_token=…").
	 *
	 * @return string
	 */
	protected function getAccessToken()
	{
		if( !$GLOBALS['TL_CONFIG']['fb_app_id'] || !$GLOBALS['TL_CONFIG']['fb_app_secret'] )
		{
			throw new \Exception( 'Cannot generate access token - App ID or App Secret missing.' );
		}

		$tokenUrl = 'https://graph.facebook.com/oauth/access_token?client_id='.$GLOBALS['TL_CONFIG']['fb_app_id'].'&client_secret='.$GLOBALS['TL_CONFIG']['fb_app_secret'].'&grant_type=client_credentials';
		return file_get_contents( $tokenUrl );
	}


	/**
	 * Returns the album data.
	 *
	 * @return stdClass
	 */
	protected function getAlbumData()
	{
		// check if album data is already present
		if( $this->objAlbumData !== null )
		{
			return $this->objAlbumData;
		}

		try
		{
			// get the cached result if available
			$objFile = new \File( $this->strCacheFile );

			// decode the album data
			$objAlbumData = json_decode( $objFile->getContent() );

			// check if album data is present
			if( is_object( $objAlbumData ) )
			{
				if( isset( $objAlbumData->images ) )
				{
					$this->objAlbumData = $objAlbumData;
					return $this->objAlbumData;
				}
			}

			// initialize album data
			$objAlbumData = new \stdClass();

			// retrieve album title
			$objData = json_decode( file_get_contents( 'https://graph.facebook.com/' . $this->strAlbumId . '?fields=id,name&'.$this->getAccessToken() ) );
			$objAlbumData->name = $objData->name;

			// prepare images array
			$images = array();

			// build graph URL (fetch as many images as possible)
			$graphUrl = 'https://graph.facebook.com/' . $this->strAlbumId . '/photos?fields=id,name,album,images,width,height,source&limit=1000&'.$this->getAccessToken();

			do
			{
				// get result
				$result = json_decode( file_get_contents( $graphUrl ) );

				// check for result
				if( !$result )
					break;

				// merge images
				$images = array_merge( $images, $result->data );

				// get the next page
				$graphUrl = $result->paging->next;
			}
			while( $result->paging->next );

			// set the image data
			$objAlbumData->images = $images;

			// cache into file
			$objFile->write( json_encode( $objAlbumData ) );

			// save in object
			$this->objAlbumData = $objAlbumData;

			// return the album data
			return $this->objAlbumData;
		}
		catch( \Exception $e )
		{
			\System::log('Error while retrieving data for Facebook album '.$this->strAlbumId.': '.$e->getMessage(), __METHOD__, TL_ERROR);
			return new \stdClass();
		}
	}


	/**
	 * Processes the facebook image object and returns src, href, width and height
	 * @param object
	 * @return array
	 */
	protected function processImage( $objImage, $maxWidth = 0 )
	{
		// get the image source and megapixel
		$fullSrc = $objImage->source;
		$fullMp = $objImage->width * $objImage->height;
		$fullWidth = $objImage->width;
		$fullHeight = $objImage->height;

		// set the thumb source to the original image source
		$thumbSrc = $fullSrc;
		$thumbWidth = $fullWidth;
		$thumbHeight = $fullHeight;
		$srcSet = $fullSrc;

		// determine the minimum thumb width and height
		$size = deserialize( $this->size );

		// extract size
		$minWidth  = $size[0];
		$minHeight = $size[1];

		// check for maximum width
		if( $maxWidth > 0 )
			$minWidth = $minWidth > 0 ? min( $minWidth, $maxWidth ) : $maxWidth;

		// whether to use thumbnails
		$useThumb =  $minWidth > 0 || $minHeight > 0;

		// check if there is additional image data
		if( is_array( $objImage->images ) )
		{
			$imgs = array();

			foreach( $objImage->images as $img )
			{
				$mp = $img->width * $img->height;

				if( $mp > $fullMp )
				{
					$fullSrc = $img->source;
					$fullMp = $mp;
					$fullWidth = $img->width;
					$fullHeight = $img->height;	
				}

				$imgs[ $mp ] = $img;
			}

			if( $useThumb )
			{
				// search for the first image with the minimum width and height
				ksort( $imgs );
				foreach( $imgs as $img )
				{
					if( $img->width >= $minWidth && $img->height >= $minHeight )
					{
						$thumbSrc = $img->source;
						$thumbWidth = $img->width;
						$thumbHeight = $img->height;
						break;
					}
				}

				// build srcset
				$srcSet = array();
				foreach( $imgs as $img )
				{
					$x = round( $img->width / $thumbWidth, 2 );
					if( $x >= 0.25 && $x < 4 )
						$srcSet[] = $img->source.' '.$x.'x';
				}
				$srcSet = implode(', ',$srcSet);
			}
		}

		return array
		(
			'href'   => $fullSrc,
			'src'    => $thumbSrc,
			'srcset' => $srcSet,
			'width'  => $thumbWidth,
			'height' => $thumbHeight
		);
	}
}

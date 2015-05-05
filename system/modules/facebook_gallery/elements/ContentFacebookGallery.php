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
			if( count( $matches ) > 1 )
				$this->strAlbumId = $matches[1];
		}

		// set type to gallery
		$this->type = 'gallery';

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		// prepare images array
		$images = array();

		// check if we have an album id
		if( !$this->strAlbumId )
			return;

		// build graph URL (fetch as much images as possible)
		$graphUrl = 'http://graph.facebook.com/' . $this->strAlbumId . '/photos?fields=id,images,width,height,source&limit=1000';

		do
		{
			// get result
			$result = json_decode( file_get_contents( $graphUrl . '&offset=' . count( $images ) ) );

			// check for result
			if( !$result )
				return;

			// merge images
			$images = array_merge( $images, $result->data );
		}
		while( $result->paging->next );

		// Limit the total number of items
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
			$page = \Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				return;
			}

			// Set limit and offset
			$offset = ($page - 1) * $this->perPage;
			$limit = min($this->perPage + $offset, $total);

			$objPagination = new \Pagination($total, $this->perPage, $GLOBALS['TL_CONFIG']['maxPaginationLinks'], $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$rowcount = 0;
		$colwidth = floor(100/$this->perRow);
		$intMaxWidth = (TL_MODE == 'BE') ? floor((640 / $this->perRow)) : floor(($GLOBALS['TL_CONFIG']['maxImageWidth'] / $this->perRow));
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

					// process image and set parameters for gallery
					$img = $this->processImage( $images[($i+$j)], $intMaxWidth );
					$objCell->src = $img['src'];
					$objCell->href = $img['href'];
					$objCell->imgSize = ' width="'.$img['width'].'" height="'.$img['height'].'"';
					$objCell->margin = static::generateMargin( deserialize( $this->imagemargin ) );
					$objCell->addImage = '1';

					if( $img['fullsize'] )
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

		$objTemplate = new \FrontendTemplate($strTemplate);
		$objTemplate->setData($this->arrData);

		$objTemplate->body = $body;
		$objTemplate->headline = $this->headline; // see #1603

		$this->Template->images = $objTemplate->parse();
	}


	/**
	 * Processes the facebook image object and returns src, href, width and height
	 * @param object
	 * @return array
	 */
	private function processImage( $objImage, $maxWidth = 0 )
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

		// determine the minimum thumb width and height
		$size = deserialize( $this->size );

		// extract size
		$intWidth  = $size[0];
		$intHeight = $size[1];

		// check for maximum width
		if( $intWidth > 0 )
			$intWidth = min( $intWidth, $maxWidth );

		// whether to use thumbnails
		$useThumb =  $intWidth > 0 || $intHeight > 0;

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
				ksort( $imgs );

				foreach( $imgs as $img )
				{
					if( $img->width > $size[0] && $img->height > $size[1] )
					{
						$thumbSrc = $img->source;
						$thumbWidth = $img->width;
						$thumbHeight = $img->height;
						break;
					}
				}
			}
		}

		return array
		(
			'href'   => $fullSrc,
			'src'    => $useThumb ? $thumbSrc : $fullSrc,
			'width'  => $useThumb ? $thumbWidth : $fullWidth,
			'height' => $useThumb ? $thumbHeight : $fullHeight
		);
	}
}

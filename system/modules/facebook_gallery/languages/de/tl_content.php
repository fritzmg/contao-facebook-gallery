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


$GLOBALS['TL_LANG']['tl_content']['facebook_legend'] = 'Facebook Album';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumId'] = array('Album URL oder Facebook ID','URL zum öffentlichen Facebook Album oder nur die Facebook ID des Albums.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumTitle'] = array('Albumtitel als Überschrift','Der Titel des Albums wird automatisch als Überschrift verwendet.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumCaption'] = array('Bildunterschrift anzeigen','Bildtext von Facebook als Bildunterschrift anzeigen.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort'] = array('Sortierung', 'Sortierung der Bilder.');
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_id_asc'] = 'ID (aufsteigend)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_id_desc'] = 'ID (absteigend)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_time_asc'] = 'Zeit (aufsteigend)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumSort_time_desc'] = 'Zeit (absteigend)';
$GLOBALS['TL_LANG']['tl_content']['fbAlbumTimeout'] = array('Cache Ablaufzeit', 'Individuelle Cache Ablaufzeit in Sekunden. Bei 0 wird der Cache immer umgangen. Wenn kein Wert eingetragen wird, gilt die globale Cache Ablaufzeit.');

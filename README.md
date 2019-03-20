[![](https://img.shields.io/maintenance/yes/2019.svg)](https://github.com/fritzmg/contao-facebook-gallery)
[![](https://img.shields.io/packagist/v/fritzmg/contao-facebook-gallery.svg)](https://packagist.org/packages/fritzmg/contao-facebook-gallery)
[![](https://img.shields.io/packagist/dt/fritzmg/contao-facebook-gallery.svg)](https://packagist.org/packages/fritzmg/contao-facebook-gallery)

Contao Facebook Gallery
===================

Simple extension to allow the integration of a Facebook album via a gallery content element. 

## Usage

### Facebook app

In order to be able to use the content element, you need to create a [Facebook App](https://developers.facebook.com) first. Then you can define the Facebook App ID and App Secret in the system settings of Contao, or in the page root settings.

Within the content element, you can simply provide the URL to the Facebook album, or its Facebook ID. 

### Access token

__Important:__ due to changes in Facebook's API in 2018 you can no longer access public albums of pages without a valid access token. Otherwise you would have to submit the _[Page Public Content Access](https://developers.facebook.com/docs/apps/review/feature/#reference-PAGES_ACCESS)_ app permission for review.

Thus since version `1.5.0` you have the ability to set a Facebook access token for each album. You can use [this tutorial](https://medium.com/@Jenananthan/how-to-create-non-expiry-facebook-page-token-6505c642d0b1) on how to create an access token _that never expires_.

_Note:_ in order for this to work, your Facebook App must have added the _Facebook Login_ Product and there the setting _Client OAuth Login_ must be enabled.

![Facebook Login settings](https://github.com/inspiredminds/contao-facebook-gallery/raw/master/facebook-login-settings.png)

### Image size

You can also define an image width and/or height (the image size mode will be ignored) - however, this will not be exact. Facebook (usually) provides differently sized images for each image of the album - the content element will try to find the smallest image that is at least as large as the given width and/or height. Thus you'll probably have to resize the images via CSS, to fit in your layout. Or change the `gallery_default` template and hardcode the given size as an inline style. 

Additionally, a srcset from the available resolutions for each image will be created.

### Sorting options

Since version `1.4.0` there is a sorting setting. Keep in mind that sorting by `ID` will only work on systems that use a 64-bit PHP environment, due to the large Facebook IDs.

## Template data

For each image of the gallery, there is additional data available via `$col->fbData`. For example `$col->fbData->id` contains the Facebook ID of the image and `$col->fbData->album->name` contains the Facebook album's name. Since version `1.5.0` this also contains the Facebook link of the image.

## Notes

* Due to a lack of implementation within the Graph API of Facebook, public albums from Facebook groups cannot be displayed.
* Currently, the images are always linked directly from Facebook and will not be cached locally.
* The Facebook Graph API data is cached locally and can be purged in the system maintenance.

## Acknowledgements

Development funded by [Kosmopiloten](http://www.kosmopiloten.at).

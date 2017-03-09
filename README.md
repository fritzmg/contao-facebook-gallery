Contao Facebook Gallery
===================

Simple extension to allow the integration of a Facebook album via a gallery content element. 

### Usage

In order to be able to use the content element, you need to create a [Facebook App](https://developers.facebook.com) first. Then you can define the Facebook App ID and App Secret in the System Settings of Contao.

Within the content element, you can simply provide the URL to the Facebook album, or its Facebook ID. 

You can also define an image width and/or height (the image size mode will be ignored) - however, this will not be exact. Facebook (usually) provides differently sized images for each image of the album - the content element will try to find the smallest image that is at least as large as the given width and/or height. Thus you'll probably have to resize the images via CSS, to fit in your layout. Or change the `gallery_default` template and hardcode the given size as an inline style. 

Additionally, a srcset from the available resolutions for each image will be created.

### Template data

For each image of the gallery, there is additional data available via `$col->fbData`. For example `$col->fbData->id` contains the Facebook ID of the image and `$col->fbData->album->name` contains the Facebook album's name.

### Notes

* Due to a lack of implementation within the Graph API of Facebook, public albums from Facebook groups cannot be displayed.
* Currently, the images are always linked directly from Facebook and will not be cached locally.
* The Facebook Graph API data is cached locally and can be purged in the system maintenance.

### Acknowledgements

Development funded by [Kosmopiloten](http://www.kosmopiloten.at).

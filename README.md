Contao Facebook Gallery
===================

Simple extension to allow the integration of a Facebook album via a gallery content element. 

### Usage

In order to be able to use the content element, you need to create a [Facebook App](https://developers.facebook.com) first. Then you can define the Facebook App ID and App Secret in the System Settings of Contao.

Within the content element, you can simply provide the URL to the Facebook album, or its Facebook ID.

### Notes

* Due to a lack of implementation within the Graph API of Facebook, public albums from Facebook groups cannot be displayed.
* Currently, the images are always linked directly from Facebook and will not be cached locally.
* The Facebook Graph API data is cached locally and can be purged in the system maintenance.

### Acknowledgements

Development funded by [Kosmopiloten](http://www.kosmopiloten.at).

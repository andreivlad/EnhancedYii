FEATURES
- easy image administration (with create, edit, delete and drag and drop reorder)
- assets under protected/views/assets
- user login, register, administration
- custom image resize, automatically add specific image for missing image files
- upload images and files, restrict files by type and size, convert images to specific
formats, add watermark to images
- usage and administration for user defined meta keywords and meta descriptions
- string functionalities (url generation, text truncate - including html)
- send e-mails with layouts and views
- drop down menu for the default admin layout
- added editMe extension for wysiwyg management (http://www.yiiframework.com/extension/editme/)
- sample administration with categories, articles and article images
	- handle created/modified dates on article and category models (in the model rules)
	- handle url auto generation and validation (in model rules and beforeValidate function; remove url from required fields)
	- add wysiwyg for article content field (in views/admin/articles/_form)
	- add checkbox for article active field (in views/admin/articles/_form)
	- add drop down for the category field (in views/admin/articles/_form)
	- deleting a category deletes all children  including articles, article images and article image files (override beforeDelete)
	- deleteing an article deletes article and article image files (override beforeDelete)



Enhanced Yii modifications
- Please remove any of these extensions accordingly to better suit your own project
/master
	* moved assets under protected/views/assets
	* added .htaccess and url management configuration in config/main.php for controller/action urls
	* added user module (http://www.yiiframework.com/extension/yii-user/) for usr related operations (login, register, management, etc)
	* added phpThumb extension for image resize and image sizes in main config
	* added imageAdmin for image administration (see modules/imageAdmin/README.md for usage instructions)
	* added upload extension (verot) for better file upload management - image resize on upload, watermark, etc.
	 (http://www.yiiframework.com/extension/upload/)
	* created seoAdmin manager to add meta description and meta keywords on any page
	* created StringHelper with url string generation and text truncate functionalities
	* added StaticPages model to exemplify url generation usage
	* added yiimailer extension email layout handling (http://www.yiiframework.com/extension/yiimailer/)
	* created drop down menu in yii admin
	* created a sample article/category/article image administration
	



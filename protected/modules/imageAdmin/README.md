- in main.php
	'import'=>array(
		 //Image admin module
      'application.modules.imageAdmin.helpers.*',
	),
	'modules'=>array(
		//administration module for images
		'imageAdmin' => array(
			 //The images upload folder
			 'uploadFolder' => '/images', //images folder is on the same level as protected
			 //The maximum file size for the uploaded images
			 'maxImageSize' => 5 * 1024 * 1024,//5MB
			 //Add watermark to the uploaded images (eg: /images/watermark.png)
			 'watermark' => ''
		),
	)

- sql for images table (you can have as many image tables as you want and name them whatever you want)
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order_index` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

*FK is not necessary

Usage:
- in the model for any images table, the 'src' attribute SHOULD NOT BE
REQUIRED in the model rules

- images are uplaoded in  uploadFolder/imageModelName

- you can set th image sizes in main.php
'params'=>array(
		// image sizes
		'image_sizes' => array(
			'100_100' => array('w'=>'100', 'h'=>'100')
		),
	)

- to get an image of a predefined size use
ImgAdmin::getImage($folder_name, $image_name, $image_size);
For instance
ImgAdmin::getImage('ArticleImage', $images->src, '100_100');


- now just create an admin for your table (say articles for example) table with GII
and in the views/admin/article/update.php file add

<?php 
	//ImageAdminModule
	ImgAdmin::initImageAdmin('ImageModelName', $model->id);
?>

Features:
- drag and drop image reorder
- add, edit, delete (also deletes resized cache images) images
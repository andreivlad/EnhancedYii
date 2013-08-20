Manage meta keywords and meta description

Add this in the head section of your layout
<?php echo SeoAdmin::getSeoElements();?>

In the controller action
SeoAdmin::setSeoElements('','','seo_item_identifier');
- either specify what keywords and/or description to use or specify the 
identifier of the seo item in the database

Manage seo items in the admin
/seoAdmin/admin/seoItem

The sql for the seo_item table
CREATE TABLE IF NOT EXISTS `xlt_seo_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_keywords` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
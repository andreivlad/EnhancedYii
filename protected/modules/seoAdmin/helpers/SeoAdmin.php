<?php
class SeoAdmin{
	public static $meta_keywords;
	public static $meta_description;
	
	/**
	 * Set specific SEO elements in a controller for the corresponding page
	 * @param type $meta_keywords - Specify the keywords directly
	 * @param type $meta_description - Specify the description directly
	 * @param type $identifier - An identifier for a SEO element in the database
	 */
	public static function setSeoElements($meta_keywords = '', $meta_description = '', $identifier = ''){
		if((empty($meta_keywords) || empty($meta_description)) && !empty($identifier)){
			Yii::import('application.modules.seoAdmin.models.SeoItem');
			
			$seoItem = SeoItem::model()->findByAttributes(array(
				 'identifier' => $identifier
			));
			
			if(!empty($seoItem->meta_keywords)){
				SeoAdmin::$meta_keywords = $seoItem->meta_keywords;
			}
			if(!empty($seoItem->meta_description)){
				SeoAdmin::$meta_description = $seoItem->meta_description;
			}
		}
		
		
		//Set meta keywords
		if(!empty($meta_keywords)){
			SeoAdmin::$meta_keywords = $meta_keywords;
		}
		
		//Set meta description
		if(!empty($meta_description)){
			SeoAdmin::$meta_description = $meta_description;
		}
	}
	
	/*
	 * Gets meta keywords and meta description for the current request
	 */
	public static function getSeoElements(){
		$seo_content = '';
		
		if (!empty(SeoAdmin::$meta_keywords)) {
			$seo_content .= '<meta name="keywords" content="'.SeoAdmin::$meta_keywords.'" />';
		}

		if (!empty(SeoAdmin::$meta_description)) {
			$seo_content .= '<meta name="description" content="'.SeoAdmin::$meta_description.'" />';
		}
		
		return $seo_content;
	}
}
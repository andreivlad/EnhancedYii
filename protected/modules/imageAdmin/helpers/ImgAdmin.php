<?php
class ImgAdmin{
	/**
	 * Initializes the image admin
	 * @param type $imageModelName - The name of the model representing the images
	 * @param type $parent_id - The id of the image parent item
	 */
	public static function initImageAdmin($imageModelName, $parent_id){
		ImgAdmin::register('img.css');
		
		Yii::app()->clientScript->registerCoreScript('jquery');
		ImgAdmin::register('jquery-ui-1.10.3.custom.min.js');
		ImgAdmin::register('jquery.form.min.js');
		ImgAdmin::register('img.js');
		echo 
		'<div id="img-admin" rel="'.$imageModelName.'.'.$parent_id.'"><div class="loader"></div></div>';
	}
	
	/**
	 * Registers module assets
	 * @param type $file
	 * @return string
	 */
	private static function register($file)
	{
		 $url = Yii::app()->getAssetManager()->publish(
		 Yii::getPathOfAlias('application.modules.imageAdmin.views.assets'));

		 $path = $url . '/' . $file;
		 if(strpos($file, 'js') !== false)
			  return Yii::app()->clientScript->registerScriptFile($path);
		 else if(strpos($file, 'css') !== false)
			  return Yii::app()->clientScript->registerCssFile($path);

		 return $path;
	}
	
	public static function getImage($folder, $src, $size='third'){
		$src = Yii::app()->request->baseUrl.Yii::app()->getModule('imageAdmin')->uploadFolder.'/'.$folder.'/'.$src;
		require_once getcwd().'/protected/extensions/phpthumb/ThumbLib.inc.php';
		$sizes = Yii::app()->params['image_sizes'];
		if(!(file_exists(getcwd().$src) && is_file(getcwd().$src))) { $src = '/images/no_image/no_image.jpg'; } // check if image exists on disk, if not return "no image" image
		if(empty($sizes[$size])){ return $src; } // check if resize name is valid
		// check if image resized exists, if not, resize it
		$pathinfo = pathinfo($src);
		if(!file_exists(getcwd().$pathinfo['dirname'].'/thumbs/'.$pathinfo['filename'].'_'.$sizes[$size]['w'].'_'.$sizes[$size]['h'].'.'.$pathinfo['extension'])) { 
			if(!file_exists(getcwd().$pathinfo['dirname'].'/thumbs/')){
				mkdir(getcwd().$pathinfo['dirname'].'/thumbs/',0777);
			}
			PhpThumbFactory::create(getcwd().$src)->adaptiveResize($sizes[$size]['w'], $sizes[$size]['h'])->save(getcwd().$pathinfo['dirname'].'/thumbs/'.$pathinfo['filename'].'_'.$sizes[$size]['w'].'_'.$sizes[$size]['h'].'.'.$pathinfo['extension']);
		}
		$src = $pathinfo['dirname'].'/thumbs/'.$pathinfo['filename'].'_'.$sizes[$size]['w'].'_'.$sizes[$size]['h'].'.'.$pathinfo['extension'];		
		return $src;
	}
	
	public static function deleteImageFile($file = '', $folder = ''){
		//Delete the main file
		$uploadFolder = Yii::app()->getModule('imageAdmin')->uploadFolder;
		if(file_exists(Yii::app()->basePath.'/..'.$uploadFolder.'/'.$folder.'/'.$file)){
			unlink(Yii::app()->basePath.'/..'.$uploadFolder.'/'.$folder.'/'.$file);
		}

		//Delete the image cache
		$image_sizes = Yii::app()->params['image_sizes'];
		if(!empty($image_sizes)){
			foreach ($image_sizes as $image_size){
				$thumb = preg_replace('~\.(?!.*\.)~', '_'.$image_size['w'].'_'.$image_size['h'].'.', $file);
				if(file_exists(Yii::app()->basePath.'/..'.$uploadFolder.'/'.$folder.'/thumbs/'.$thumb)){
					unlink(Yii::app()->basePath.'/..'.$uploadFolder.'/'.$folder.'/thumbs/'.$thumb);
				}
			}
		}
	}
}
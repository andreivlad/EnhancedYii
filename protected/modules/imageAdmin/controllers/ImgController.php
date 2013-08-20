<?php
/**
 * Handles image related requests via ajax
 */
class ImgController extends Controller
{
	var $layout = false;
	var $uploadFolder;
	var $maxImageSize;
	var $watermark = false;
	public function filters()
	{
		$this->uploadFolder = Yii::app()->getModule('imageAdmin')->uploadFolder;
		$this->maxImageSize = Yii::app()->getModule('imageAdmin')->maxImageSize;
		$watermark = Yii::app()->getModule('imageAdmin')->watermark;
		if(!empty($watermark)){
			$this->watermark = $watermark;
		}
		return array(
			'accessControl', // perform access control
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules(){
		return array(
			array('allow', // only admin users have access to this controller
				'actions'=>array('getImages', 'reorderImages', 'addImage', 'editImage', 'deleteImage'),
				'users' => Yii::app()->getModule('user')->getAdmins()
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	
	public function actionGetImages(){
		if(Yii::app()->request->isAjaxRequest){
			$imageModelName = Yii::app()->request->getPost('imageModelName','');
			$parentId = Yii::app()->request->getPost('parentId','');
			
			if(!empty($imageModelName) && !empty($parentId)){
				$Image = new $imageModelName();
				
				$images = $Image::model()
              ->findAll(array(
                  'condition' => 'parent_id=:parent_id',
                  'order' => 'order_index ASC',
						'params' => array(
							 ':parent_id' => $parentId
						)
                ));
			}
			
			$this->render('getImages', array(
				 'images' => $images,
				 'model_name' => $imageModelName
			));
		}
	}
	
	public function actionReorderImages(){
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
			$image_indexes = Yii::app()->request->getPost('image_indexes', '');
			$order_indexes = Yii::app()->request->getPost('order_indexes', '');
			$model_name = Yii::app()->request->getPost('model_name', '');
			
			if(!empty($image_indexes) && !empty($order_indexes) && !empty($model_name)){
				$image_indexes = explode(',', $image_indexes);
				$order_indexes = explode(',', $order_indexes);
				
				$command = Yii::app()->db->createCommand();
				$Image = new $model_name();
				
				foreach ($image_indexes as $key => $image_index) {
					$command->update($Image::model()->tableSchema->name, array(
						'order_index'=>$order_indexes[$key],
				  ), 'id=:id', array(':id'=>$image_index));
				}
			}
		}
	}
	
	public function actionAddImage(){
		if(Yii::app()->request->isAjaxRequest){
			if(Yii::app()->request->isPostRequest){
				$model_name = Yii::app()->request->getPost('model_name', '');
				$parent_id = Yii::app()->request->getPost('parent_id', '');
				if(!empty($model_name) && !empty($parent_id)){
					$Image = new $model_name();
					
					$Image->attributes = array(
						 'title' => Yii::app()->request->getPost('title', ''),
						 'parent_id' => $parent_id
					);
					
					$response = array(
						 'success' => false
					);
					
					//Set the upload extension options
					Yii::import('application.extensions.upload.Upload');
					$Upload = new Upload( (isset($_FILES['src']) ? $_FILES['src'] : null) );
					$Upload->allowed = array('image/*'); 
					$Upload->image_convert = 'jpeg';
					$Upload->file_max_size = $this->maxImageSize;
					if(!empty($this->watermark)){
						$Upload->image_watermark = Yii::app()->getBasePath().'/..'.$this->watermark;
					}
					$destPath = Yii::app()->getBasePath().'/../'.$this->uploadFolder.'/'.$model_name;
					
					
					if($Image->validate()){
						$Upload->process($destPath);
						
						if ($Upload->uploaded) {
							if ($Upload->processed) {
								$Image->src = $Upload->file_dst_name;
								$Image->save();
								$response['success'] = true;
								echo json_encode($response);
							}
						}
					}
					
					if(!$response['success']){
						$response['success'] = false;
						if(!empty($Upload->error)){
								$response['errors'][] = $Upload->error;
						}
								
						if(!empty($Image->errors)){
							foreach ($Image->errors as $error){
								$response['errors'][]= $error[0];
							}
						}
						echo json_encode($response);
					}
				}
			}else{
				$this->render('addImage', array(
					 'model_name' => Yii::app()->request->getParam('model_name'),
					 'parent_id' => Yii::app()->request->getParam('parent_id')
				));
			}
		}
	}
	
	public function actionEditImage(){
		if(Yii::app()->request->isAjaxRequest){
			if(Yii::app()->request->isPostRequest){
				$model_name = Yii::app()->request->getPost('model_name', '');
				$image_id = Yii::app()->request->getPost('image_id', '');
				if(!empty($model_name) && !empty($image_id)){
					$Image = new $model_name();
					$img = $Image->findByPk($image_id);
					$response = array(
						 'success' => false
					);
					
					$img->attributes = array(
						 'title' => Yii::app()->request->getPost('title', ''),
					);
					
					if($img->validate()){
						if(isset($_FILES['src'])){
							//Set the upload extension options
							Yii::import('application.extensions.upload.Upload');
							$Upload = new Upload($_FILES['src']);
							$Upload->allowed = array('image/*'); 
							$Upload->image_convert = 'jpeg';
							$Upload->file_max_size = $this->maxImageSize;
							if(!empty($this->watermark)){
								$Upload->image_watermark = Yii::app()->getBasePath().'/..'.$this->watermark;
							}
							$destPath = Yii::app()->getBasePath().'/../'.$this->uploadFolder.'/'.$model_name;

							$Upload->process($destPath);
							if ($Upload->uploaded) {
								if ($Upload->processed) {
									//Delete old image
									ImgAdmin::deleteImageFile($img->src, $model_name);
									
									$img->src = $Upload->file_dst_name;
									$img->save();
									
									$response['success'] = true;
									$response['src'] = ImgAdmin::getImage($model_name, $img->src, '100_100');
									echo json_encode($response);
								}
							}
						}else{
							$img->save();
							$response['success'] = true;
							echo json_encode($response);
						}
					}
					
					if(!$response['success']){
						$response['success'] = false;
						if(!empty($Upload->error)){
								$response['errors'][] = $Upload->error;
						}
								
						if(!empty($Image->errors)){
							foreach ($Image->errors as $error){
								$response['errors'][]= $error[0];
							}
						}
						echo json_encode($response);
					}
				}
			}else{
				$image_id = Yii::app()->request->getParam('image_id', '');
				$model_name = Yii::app()->request->getParam('model_name', '');
				if(!empty($image_id)){
					$Image = new $model_name();
					$img = $Image->findByPk($image_id);

					$this->render('editImage', array(
						 'model_name' => Yii::app()->request->getParam('model_name'),
						 'img' => $img
					));
				}
			}
		}
	}
	
	public function actionDeleteImage(){
		if(Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest){
			$image_id = Yii::app()->request->getPost('image_id', '');
			$model_name = Yii::app()->request->getPost('model_name', '');
			
			if(!empty($image_id) && !empty($model_name)){
				$Image = new $model_name();
				$img = $Image->findByPk($image_id);
				
				ImgAdmin::deleteImageFile($img->src, $model_name);
				
				//Delete from db
				$img->delete();
			}
		}
	}
}
<?php

/**
 * This is the model class for table "{{articles}}".
 *
 * The followings are the available columns in table '{{articles}}':
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $content
 * @property integer $category_id
 * @property integer $active
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property ArticleImages[] $articleImages
 * @property Categories $category
 */
class Articles extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Articles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{articles}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, category_id', 'required'),
			array('category_id, active', 'numerical', 'integerOnly'=>true),
			array('title, url', 'length', 'max'=>255),
			/*Auto generate created and modified dates*/
			array('created', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
			array('modified','default', 'value'=> date('Y-m-d H:i:s'), 'setOnEmpty'=>true,'on'=>'insert'),
			array('modified','default', 'value'=> date('Y-m-d H:i:s'), 'setOnEmpty'=>false,'on'=>'update'),
			/*Url rules*/
			array('url', 'match', 'not' => true, 'pattern' => '/[^a-zA-Z0-9_-]/i', 'message' => 'The url is not valid'),
			array('url', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, url, content, category_id, active, created, modified', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	  * Override before validate to auto generate a valid and unque url
	  * @return boolean
	  */
	 protected function beforeValidate(){
		 parent::beforeValidate();
		 //If the model is being created and the name has been filled in
		 if(empty($this->id) && !empty($this->title) && empty($this->url)){
			$url = StringHelper::makeUrl($this->title);
			$exists = Articles::model()->exists('url=:url', array(
				 ':url' => $url
			));
			$no = 1;
			 
			while($exists){
				$url .= $no;
				$exists = Articles::model()->exists('url=:url', array(
					':url' => $url
				));
				$no++;
			}
			$this->url = $url;
		 }
		 return true;
	 }
	 
	 protected function beforeDelete() {
		 parent::beforeDelete();
		 
		 //Delete the article images for this article
		 $images = ArticleImages::model()->findAllByAttributes(array(
			  'parent_id' => $this->id
		 ));
		 
		 if(!empty($images)){
			 foreach ($images as $image){
				 ImgAdmin::deleteImageFile($image->src, 'ArticleImages');
				 $image->delete();
			 }
		 }
		 
		 return true;
	 }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'articleImages' => array(self::HAS_MANY, 'ArticleImages', 'parent_id'),
			'category' => array(self::BELONGS_TO, 'Categories', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'url' => 'Url',
			'content' => 'Content',
			'category_id' => 'Category',
			'active' => 'Active',
			'created' => 'Created',
			'modified' => 'Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('active',$this->active);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
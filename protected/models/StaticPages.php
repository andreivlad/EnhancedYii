<?php

/**
 * This is the model class for table "{{static_pages}}".
 *
 * The followings are the available columns in table '{{static_pages}}':
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $content
 * @property string $created
 */
class StaticPages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StaticPages the static model class
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
		return '{{static_pages}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, url, content, created', 'required'),
			array('title, url', 'length', 'max'=>255),
			//URL rules
			array('url', 'match', 'not' => true, 'pattern' => '/[^a-zA-Z_-]/i', 'message' => 'The url is not valid'),
			array('url', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, url, content, created', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	  * Override before validate to auto generate a valid and unque url
	  * @return boolean
	  */
	 protected function beforeValidate(){
		 //If the model is being created and the title has been filled in
		 if(empty($this->id) && !empty($this->title) && empty($this->title)){
			$url = StringHelper::makeUrl($this->title);
			$exists = StaticPages::model()->exists('url=:url', array(
				 ':url' => $url
			));
			$no = 1;

			//Generate urls of type url1, url2, until one of them is unique
			while($exists){
				$exists = StaticPages::model()->exists('url=:url', array(
					':url' => $url.$no
				));
				$no++;
			}
			$this->url = $url;
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
			'created' => 'Created',
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
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
 <?php

/**
 * This is the model class for table "{{seo_items}}".
 *
 * The followings are the available columns in table '{{seo_items}}':
 * @property integer $id
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $url
 * @property string $created
 */
class SeoItem extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SeoItem the static model class
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
        return '{{seo_items}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('meta_keywords, meta_description, identifier', 'required'),
            array('meta_keywords, meta_description, identifier', 'length', 'max'=>255),
				array('created', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, meta_keywords, meta_description, identifier, created', 'safe', 'on'=>'search'),
        );
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
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'identifier' => 'Identifier',
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
        $criteria->compare('meta_keywords',$this->meta_keywords,true);
        $criteria->compare('meta_description',$this->meta_description,true);
        $criteria->compare('identifier',$this->identifier,true);
        $criteria->compare('created',$this->created,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
} 
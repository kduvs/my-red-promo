<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;
    public $title;
    public $path = '';

    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->path = 'uploads/' . $this->title . '.' . $this->image->extension;
            $this->image->saveAs($this->path);
            return true;
        } else {
            return false;
        }
    }
}
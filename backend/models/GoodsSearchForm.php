<?php
namespace backend\models;
use yii\base\Model;

class GoodsSearchForm extends Model
{
    public $name;
    public $sn;


    public function rules()
    {
    return [
      [['name','sn'],'safe'],
    ];
    }
}
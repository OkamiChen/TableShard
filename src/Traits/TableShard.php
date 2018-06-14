<?php

namespace OkamiChen\TableShard\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait TableShard {
    
    /**
     * 分表数量,最好为8的倍数
     * @var int 
     */
    public $shardNum    = 8;
    
    /**
     * 根据那个表取余
     * @var string 
     */
    public $shardKey    = 'id';
    
    /**
     * 按照指定的数字取余数
     */
    
    public function getShardTable(){
        $key    = $this->shardKey;
        $value  = $this->$key;
        return $this->table.'_'. sprintf('%02d', $value % $this->shardNum);
    }
        
    /**
     * Define a one-to-one relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function haveOne($related, $foreignKey = null, $localKey = null)
    {
        $model          = new $related();
        $tableName      = $this->getShardTable();
        $model->setTable($tableName);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();
        
        return new HasOne($model->newQuery(), $this, $foreignKey, $localKey);
        //return new HasOne($model->newQuery(), $this, $model->getTable().'.'.$foreignKey, $localKey);
    }
}


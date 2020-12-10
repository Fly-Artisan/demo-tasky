<?php
/**
 * @author  K.B. Brew <flyartisan@gmail.com>
 * @package FLY\Model\Algorithm
 * @version 3.0.0
 */

namespace FLY\Model\Algorithm;

use FLY_ENV\Util\Model\QueryBuilder;

/**
 * @trait  SearchQuery
 * @todo   Helps invoke sql query 
 */

trait SearchQuery {


    protected static function getData($keys,$self) 
    {
        if(is_array($keys)) {
            foreach($keys as $key => $value) {
                $self->{":$key"} = $value;
            }
        } else if((is_numeric($keys) || is_string($keys))&& isset($self->getPks()[0])) {
            $key = trim($self->getPks()[0]);
            $self->{":$key"} = $keys;
        }
        return $self;
    }

    public function __get(string $var)
    {
        if($this->field_is_valid($var,$this->getActiveModel())) {
            $result = $this->find(":{$var}");
            $data   = (array) $this->fields_mem;
            $where  = [];
            foreach($data as $key => $value) {
                if(is_array($value) || is_object($value)) continue;

                $value = trim($value);
                $key = trim($key);
                if($value <> "" && ($key === $var || in_array($key,$this->getPks()))){
                    array_push($where,"{$key}='{$value}'");
                } elseif($value <> "" && $key <> "") {
                    array_push($where,"{$key}='{$value}'");
                }
            }

            if($where[0]??false) {
                $result->where(implode(' AND ',$where));
            }
            $result = $result->end()->value();
            return $this->fields_mem->{$var} = count($result) === 1 ? $result[0]->{$var}: $result;
        } else throw new \Exception("The field name '{$var}' does not exists in class '{$this->activeModelName}'.");
    }

    
    /**
     * @method object find()
     * @param array ...$args
     * @return object
     * @todo Constructs a search query
     */

    public function blueprint()
    {
        return $this->pdo->executeSearchQuery("DESCRIBE {$this->getTableName()}");
    }

    public function distinct(...$fields): object
    {
        return new class($this,$fields) extends QueryServer implements ISearchQuery {

            use SearchEnd;

            /**
             *
             * @param string $class
             * @param array ...$args
             */
            public function __construct(QueryBuilder $model, array ...$args)
            {  
                parent::__construct($model,$args,"","DISTINCT");
            }
            
            
        };
    }

    public function find(...$fields): object
    {
        return new class($this,$fields) extends QueryServer implements ISearchQuery {
            
            use SearchEnd;

            /**
             *
             * @param string $class
             * @param array ...$args
             */
            public function __construct(QueryBuilder $model, array ...$args)
            {  
                parent::__construct($model,$args);
            }
            
        };
    }
}
<?php
/**
 * @author  K.B. Brew <flyartisan@gmail.com>
 * @package FLY\Model
 * @version 3.0.0
 */

namespace FLY_ENV\Util\Model;

use Exception;
use FLY\Model\Algorithm\{
    Config,
    DeleteQuery,
    SaveQuery,
    SearchQuery,
    UpdateQuery
};
use FLY\Model\SQLPDOEngine;

/**
 * @class  QueryBuilder
 * @todo   Organizes model fields
 */

abstract class QueryBuilder extends Config {

    /** 
     * @var array|null $methods
     * @todo Allocates memory for custom methods
     */
    private static ?array $methods                  = [];
    
    
    /** 
     * @var array $pk_names
     * @todo Holds model primary fields
     */
    protected array $pk_names                      = [];
    

    /** 
     * @var array $fk_names
     * @todo Holds model foreign key fields
     */
    protected array $fk_names                      = [];

    
    /**
     * @var string
     * @todo Stores active model name
     */
    private string $activeModelName                = '';


    /**
     * @var QueryBuilder|null 
     * @todo Stores a model object
     */
    private ?QueryBuilder $activeModelObject;

    /**
     * @var SQLPDOEngine|null 
     * @todo Stores a model object
     */
    private ?SQLPDOEngine $pdo;

    
    /**
     * @var array $activeModelVars
     * @todo Stores active model's fields 
     */
    private array $activeModelFields   = [];


    /**
     * @var object|null $fields_mem
     * @todo Stores model fields
     */
    private ?object $fields_mem        = null;

    
    use SearchQuery;

    use SaveQuery;

    use UpdateQuery;

    use DeleteQuery;

    /**
     * @param QueryBuilder $model
     */

    public function __construct(QueryBuilder $model)
    {
        $this->activeModelName   = $model->get_name();

        $this->activeModelObject = $model;
        $this->connectToDatabase();
        $this->setChildClassFields();
    }


    /**
     * @method void __destruct()
     * @return void
     */
    public function __destruct()
    {
        self::$methods = null;
    }

    private function commitFieldsToMem()
    {
        $this->fields_mem = (object) [];       
        foreach($this->activeModelFields as $fields_mem) {
            $this->fields_mem->{$fields_mem} = $this->{$fields_mem};
        }
    }

    private function connectToDatabase()
    {
        $config = $this->getConfigurations();   
        $this->pdo = new SQLPDOEngine(
            $this->activeModelObject,
            $config->getHost(),
            $config->getModel(),
            $config->getUser(),
            $config->getPassword()
        );
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function getPks()
    {
        return $this->pk_names;
    }

    public function getActiveModel()
    {
        return $this->activeModelObject;
    }

    public function get_active_model_fields()
    {
        return $this->activeModelFields;
    }

    private function getConfigurations(): Config 
    {
        $this->setUp(
            $this->activeModelObject->connection()
        );
        return $this;
    }

    private function getActiveModelName()
    {
        return $this->getTableName($this->activeModelName);
    }

    static protected function searchModelName(string $class_name)
    {
        $splitance = explode('\\',$class_name);
        return end($splitance);
    }

    public function getTableName(string $activeModelName)
    {
        return self::searchModelName($activeModelName);
    }

    public function get_table_name()
    {
        return $this->getActiveModelName();
    }

    /**
     * @method void createProc()
     * @param string $name
     * @param callable $callback
     * @return void
     */
    public static function createProc(string $name,callable $callback)
    {
        self::$methods[$name] = (object)[
            'action' => $callback,
            'type'   => 'proc'
        ];
    }

    public static function createQuery(string $name,callable $callback)
    {
        self::$methods[$name] = (object)[
            'action' => $callback,
            'type'   => 'qry'
        ];
    }

    /**
     * @method void createMethod
     * @param string $name
     * @param callable $callback
     * @return void
     */
    public static function createMethod(string $name,callable $callback)
    {
        self::$methods[$name] = (object)[
            'action' => $callback,
            'type'   => 'method'
        ]; 
    }


    /**
     * @method void __call()
     * @param string $name
     * @param array $arguments
     * @return QueryBuilder
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        if(is_array(self::$methods) && array_key_exists($name,self::$methods)) {

            $action = self::$methods[$name]->action;
            
            switch(self::$methods[$name]->type) {
                
                case 'proc':
                    $this->cleanProcedureArgs($arguments);
                    if(!empty($arguments)) return $action($this,...$arguments);
                    return $action($this,$arguments);
                    
                case 'qry':
                    if(!empty($arguments)) return $action($this,...$arguments);
                    return $action($this,$arguments);

                default:
                    if(!empty($arguments)) $action($this,...$arguments);
                    else $action($this,$arguments);
                    
                    return $this;
            }
        }
        throw new Exception("The method '{$name}' does not exists");
    }


    private function cleanProcedureArgs(array &$args)
    {
        foreach($args as $key => $arg) {

            if(is_object($arg)) continue;
            if(is_array($arg)) {
                $this->cleanProcedureArgs($arg);
                $args[$key] = $arg;
            } else $args[$key] = "'{$arg}'";
        }
    }

    
    /**
     * @method void setChildClassFields
     * @return void
     * @todo Set's enlist active model fields
     */

    private function setChildClassFields()
    {
        $child_model_vars        = get_class_vars($this->activeModelName);
        $builder_vars            = get_class_vars(QueryBuilder::class);
        
        foreach($child_model_vars as $key => $val) {
            if(!\array_key_exists($key,$builder_vars)) {
                $this->activeModelFields[] = $key;
            }
        }
    }

    static protected function make_auto_save(array $data, QueryBuilder $model)
    {
        foreach($data as $key => $value) {
            if(!property_exists($model,$key)) continue;
            $model->{":$key"} = $value;
        }
        return $model->save();
    }

    static protected function make_auto_update(array $data, QueryBuilder $model)
    {
        foreach($data as $key => $value) {
            if(!property_exists($model,$key)) continue;
            $model->{":$key"} = $value;
        }
        return $model->update();
    }

    static protected function make_auto_set(array $data, QueryBuilder $model)
    {
        foreach($data as $key => $value) {
            if(!property_exists($model,$key)) continue;
            $model->{":$key"} = $value;
        }
        return $model;
    }

    static private function generateAppendTuples(array $data,$field)
    {
        $result = [];
        foreach($data as $dt) {
            array_push($result,$dt->{$field});
        }
        return $result;
    }

    static protected function _append_process(string $reference_model_name,$model,$callback)
    {
        $pks = $model->getPks();
        $numOfPks = count($pks);
        $hasTuple = false;
        if($numOfPks > 0) {
            $fql       = [];
            $count     = 1;    
            foreach($pks as $pkname) {
                $refModelIds = $callback($pkname);
                
                $refModelIds = self::generateAppendTuples($refModelIds,$pkname);
                if(count($refModelIds) > 0) {
                    $fql[":{$pkname}"] = (object)[
                        'not in'=> $refModelIds
                    ];
                    if($count++ < $numOfPks) {
                        array_push($fql,'&');
                    }
                    $hasTuple = true;
                }
            }
            $query = $model->append()
                    ->find()
                    ->from($reference_model_name);
            if($hasTuple){ 
                $query = $query->where($fql);
            }
            return $query->end();
        } else throw new Exception("Error: Primary key fields of the {$model->get_table_name()} model.");
    }

    static protected function _append_where(string $reference_model_name,array $where,$model)
    {
        $data_exists = $model->find()->where(...$where)->end()->count() > 0;
        if(!$data_exists) {
            return $model->append()
                    ->find()
                    ->from($reference_model_name)
                    ->where(...$where)
                    ->end();
        }
        return null;
    }

    static protected function make_auto_append(string $reference_model_name, QueryBuilder $model)
    {
        try {
            return self::_append_process(
                $reference_model_name,
                $model,
                fn($pkname) => $model->find()->end()->by_field($pkname)
            );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function apply()
    {
        $this->commitFieldsToMem();
    }

    public function mem()
    {
        return $this->fields_mem;
    }

    public function where(array $search_query)
    {
        $result  = [];
        if($this->isShallowFiltering($search_query)) {
            $result = $this->find()->end()->filter($search_query);
        } else if($this->isDeepFiltering($search_query)){
            $result = $this->find()->where($search_query)->end()->value();
        }
        return $result;
    }

    protected static function _clear(QueryBuilder $model)
    {
        return $model->delete()->end();
    }

    protected static function _index(QueryBuilder $self,int $index)
    {
        return $self->find()->end()->by_index($index);
    }

    protected static function _field(QueryBuilder $self,string $field_name)
    {
        return $self->find()->end()->by_field($field_name);
    }

    protected static function _value(QueryBuilder $self,string $field_value)
    {
        return $self->find()->end()->by_value($field_value);
    }

    protected static function _field_value(QueryBuilder $self,string $field_name,string $field_value)
    {
        return $self->find()->end()->by_field_value($field_name, $field_value);
    }

    protected static function _match_field(QueryBuilder $self,string $pattern)
    {
        return $self->find()->end()->by_match_field($pattern);
    }

    protected static function _match_value(QueryBuilder $self,string $pattern)
    {
        return $self->find()->end()->by_match_value($pattern);
    }

    protected static function _match_field_value(QueryBuilder $self,string $field_pattern,string $value_pattern)
    {
        return $self->find()->end()->by_match_field_value($field_pattern,$value_pattern);
    }

    protected static function _all(QueryBuilder $self)
    {
        return $self->find()->end()->value();
    }

    protected static function _first(QueryBuilder $self)
    {
        return $self->find()->end()->first_record();
    }

    protected static function _second(QueryBuilder $self)
    {
        return $self->find()->end()->second_record();
    }

    protected static function _third(QueryBuilder $self)
    {
        return $self->find()->end()->third_record();
    }

    protected static function _middle(QueryBuilder $self)
    {
        return $self->find()->end()->middle_record();
    }

    protected static function _last(QueryBuilder $self)
    {
        return $self->find()->end()->last_record();
    }

    protected static function _count(QueryBuilder $self)
    {
        return $self->find()->end()->count();
    }

    protected static function _reverse(QueryBuilder $self)
    {
        return $self->find()->end()->reverse();
    }

    protected static function _field_null(QueryBuilder $self,string $field_name)
    {
        return $self->find()->end()->field_null($field_name);
    }

    protected static function _field_capacity(QueryBuilder $self,string $field_name)
    {
        return $self->find()->end()->field_capacity($field_name);
    }

    protected static function _field_type(QueryBuilder $self,string $field_name)
    {
        return $self->find()->end()->field_type($field_name);
    }

    protected static function _field_default(QueryBuilder $self,string $field_name)
    {
        return $self->find()->end()->field_default($field_name);
    }

    protected static function _field_extra(QueryBuilder $self,string $field_name)
    {
        return $self->find()->end()->field_extra($field_name);
    }

    protected static function _describe(QueryBuilder $self)
    {
        return $self->find()->end()->blueprint();
    }

    protected static function _is_empty(QueryBuilder $self)
    {
        return $self->find()->end()->is_empty();
    }

    protected static function _field_exists(QueryBuilder $self,string $field_name)
    {
        return $self->find()->end()->field_exists();
    }

    protected static function _value_exists(QueryBuilder $self,string $value,string $field_name)
    {
        return $self->find()->end()->value_exists($value,$field_name);
    }

    protected static function _fields(QueryBuilder $self)
    {
        return $self->find()->end()->fields();
    }
    
    protected static function _delete_where(array $expressions, QueryBuilder $model)
    {
        return $model->delete()->where(...$expressions)->end();
    }

    protected static function _delete_when(object $data, QueryBuilder $model)
    {
        return $model->indexPopper($data);
    }

    protected static function _query(string $query, QueryBuilder $model)
    {
        return $model->getPDO()->executeCRUD($query);
    }

    private function isDeepFiltering(array $search_query)
    {
        return preg_match('/^[:][a-zA-Z_][a-zA-Z0-9]*/',array_key_first($search_query));
    }

    private function isShallowFiltering(array $search_query)
    {
        return (
            array_key_exists('key',$search_query)   ||
            array_key_exists('value',$search_query) ||
            array_key_exists('match_key',$search_query) 
        );
    }
}
<?php namespace FLY\Libs\CRUD;
/**
 * @author  K.B. Brew <flyartisan@gmail.com>
 * @package FLY\Libs\CRUD
 * @version 2.0.0
 */
class AppDAO implements CRUDRepository {

    public function __construct()
    {
        $this->user = $this->modelName::instance();  
    }

    public function getUser() 
    {
        return $this->user;
    }

    public function save(object $data=null) {
        if($data <> null) $this->user = $this->modelName::push((array) $data);
        else $this->user = $this->modelName::auto_set();
        $this->user->save();
        return $this->user::last();
    }

    public function update($data=null) 
    {
        if($data <> null && is_array($data)) return $this->modelName::push_update($data);
        return $this->modelName::auto_update();
    }

    public function delete($data=null)
    {
        if($data <> null && is_array($data)) return $this->modelName::delete_when($data);
        return false;
    }

    public function deleteById($id=null) 
    {
       $model = $this->modelName;
       $this->user = new $model();
       return $this->user->delete()->whereId($id);
    }

    public function fetchById($id=null) 
    {
        return $this->user->find()->whereId($id)->end()->value();
    }

    public function fetchByIds(array $ids) 
    {
        return $this->user->find()->whereIds($ids)->end()->value();
    }

    public function fetchAll() 
    {
        return $this->modelName::all();
    }

    public function fetchFirst()
    {
        return $this->modelName::first();
    }

    public function fetchSecond()
    {
        return $this->modelName::second();
    }

    public function fetchThird()
    {
        return $this->modelName::third();
    }

    public function fetchMiddle()
    {
        return $this->modelName::middle();
    }

    public function fetchLast()
    {
        return $this->modelName::last();
    }
}
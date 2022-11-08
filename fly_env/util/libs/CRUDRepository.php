<?php namespace FLY\Libs;
/**
 * @author  K.B. Brew <flyartisan@gmail.com>
 * @package FLY\Libs
 * @version 2.0.0
 */
interface CRUDRepository {

    public function save(object $model=null);

    public function update($id=null);

    public function delete($id=null);

    public function deleteById($id=null);

    public function fetchById($id=null);

    public function fetchByIds(array $ids);

    public function fetchAll();
}
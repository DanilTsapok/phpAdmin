<?php

namespace Model;

abstract  class Data {
    const  FILE = 0;
    const DB = 1;

    private $error;
    private $user;

    public function setCurrentUser($userName){
        $this->user = $this->readUser($userName);
    }
    public function getCurrentUser() {
        return $this->user;
    }
    public function checkRight($object, $right){
        return $this->user->checkRight($object, $right);
    }

    public function readRegions($cityId){
        if($this->user->checkRight('region', 'view')){
            $this->error="";
            return $this->getRegions($cityId);
        }else{
            $this->error="You have no permissions to view regions";
            return false;
        }
    }
    protected abstract  function  getRegions($cityId);

    public function readRegion($cityId, $id){
        if($this->checkRight('region', 'view')){
            $this->error = "";
            return $this->getRegion($cityId, $id);
        }else{
            $this-> error = "You have no permissions to view region";
            return false;
        }
    }
    protected abstract function getRegion($cityId, $id);

    public function  readCities(){
        if($this ->checkRight('city','view')){
            $this ->error = "";
            return $this-> getCities();
        } else{
            $this->error = "You have no permissions to view cities";
            return false;
        }
    }
    protected  abstract  function  getCities();

    public function  readCity($id){
        if ($this->checkRight('city','view')){
            $this->error="";
            return $this->getCity($id);
        }else{
            $this->error = "You have no permissions to view city";
            return false;
        }
    }
    protected abstract function getCity($id);

    public function readUsers(){
        if ($this->checkRight('user', 'admin')){
            $this->error="";
            return $this->getUsers();
        }else{
            $this->error="You have no permissions to administrate users";
            return false;
        }
    }
    protected  abstract function getUsers();

    public function readUser($id){
        $this->error="";
        return $this->getUser($id);
    }
    protected  abstract function getUser($id);

    public  function  writeRegion(Region $region){
        if($this->checkRight('region','edit')){
            $this->error="";
            $this->setRegion($region);
            return true;
        }else{
            $this->error = "You have no permissions to edit students";
            return false;
        }
    }
    protected  abstract function setRegion(Region $region);

    public function writeCity(City $city){
        if($this->checkRight('city','edit')){
            $this->error="";
            $this->setCity($city);
            return true;
        }else{
            $this->error="You have no permissions to edit Cities";
            return false;
        }
    }
    protected  abstract function setCity(City $city);

    public function writeUser(User $user){
        if($this->checkRight('user','admin')){
            $this->error="";
            $this->setUser($user);
            return true;
        } else{
            $this->error = "You have no permissions to administrate users";
            return false;
        }
    }
    protected abstract function setUser(User  $user);

    public function removeRegion(Region $region){
        if($this->checkRight('region','delete')){
            $this->error="";
            $this->delRegion($region);
            return true;
        }else{
            $this->error="You have no permissions to delete regions";
            return false;
        }
    }
    protected abstract function delRegion(Region $region);

    public function addRegion(Region $region){
        if($this->checkRight('region','create')){
            $this->error="";
            $this->insRegion($region);
            return true;
        }else{
            $this->error = "You have no permissions to create regions";
            return false;
        }
    }
    protected abstract  function  insRegion(Region $region);

    public function removeCity($cityId)
    {
        if ($this->checkRight('city', 'delete')) {
            $this->error = "";
            $this->delCity($cityId);
            return true;
        } else {
            $this->error = "You have no permissions to create cities";
            return false;
        }
    }
    protected abstract function delCity($cityId);

    public function addCity(){
        if ($this->checkRight('city', 'create')) {
            $this->error = "";
            $this->insCity();
            return true;
        } else {
            $this->error = "You have no permissions to create cities";
            return false;
        }
    }
    protected abstract function insCity();
    public function getError(){
        if ($this->error) {
            return $this->error;
        } else {
            return false;
        }
    }
    public static function makeModel($type){
        if($type == self::FILE){
            return new FileData();
        }elseif ($type ==  self::DB){
            return new DBData(new MySQLdb());
        }
        return new FileData();
    }
}



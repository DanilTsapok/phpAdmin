<?php

namespace Model;

class Region{
    private $id;
    private $name;
    private $square;
    private $population;
    private $cityId;
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
        return $this; 
    }
    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    public function getSquare(){
        return $this->square;
    }
    public function setSquare($square){
        $this->square = $square;
        return $this;
    }
    public function getPopulation(){
        return $this->population;
    }
    public function setPopulation($population){
        $this->population = $population;
        return $this;
    }
    public function getCityId(){
        return $this->cityId;
    }
    public function setCityId($cityId){
        $this->cityId = $cityId;
        return $this;
    }
}

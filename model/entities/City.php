<?php
namespace  Model;

  class City{
      private $id;
      private $city;
      private $region;
      private $country;
    
      public function getId(){
        return $this->id;
      }
      public function setId($id){
        $this->id = $id;
        return $this;
      }
      public function getCity(){
          return $this->city;
      }
      public function setCity($city){
          $this->city = $city;
          return $this;
      }
      public function getRegion(){
          return $this->region;
      }
      public function setRegion($region){
          $this -> region = $region;
          return $this;
      }
      public function getCountry(){
          return $this->country;
      }
      public function setCountry($country){
          $this -> country = $country;
          return $this;
      }
  }
  
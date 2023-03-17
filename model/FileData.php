<?php

namespace Model;

class FileData extends Data
{
    const DATA_PATH = __DIR__ . '/../data/';
    const REGION_FILE_TEMPLATE = '/^region-\d\d.txt\z/';
    const CITY_FILE_TEMPLATE = '/^city-\d\d\z/';

    protected function getRegions($cityId)
    {
        $Regions = array();
        $conts = scandir(self::DATA_PATH . $cityId);
        foreach ($conts as $node) {
            if (preg_match(self::REGION_FILE_TEMPLATE, $node)) {
                $Regions[] = $this->getRegion($cityId, $node);
            }
        }
        return $Regions;
    }

    protected function getRegion($cityId, $id)
    {
        $f = fopen(self::DATA_PATH . $cityId . "/" . $id, "r");
        $rowStr = fgets($f);
        $rowArr = explode(";", $rowStr);
        $Region = (new Region())
            ->setId($id)
            ->setName($rowArr[0])
            ->setSquare($rowArr[1])
            ->setPopulation($rowArr[2]);
        fclose($f);
        return $Region;
    }

    protected function getCities()
    {
        $cities = array();
        $conts = scandir(self::DATA_PATH);
        foreach ($conts as $node) {
            if (preg_match(self::CITY_FILE_TEMPLATE, $node)) {
                $cities[] = $this->getCity($node);
            }
        }
        return $cities;
    }

    protected function getCity($id)
    {
        $f = fopen(self::DATA_PATH . $id . "/city.txt" , "r");
        $grStr = fgets($f);
        $grArr = explode(";", $grStr);
        fclose($f);
        $city = (new City())
            ->setId($id)
            ->setCity($grArr[0])
            ->setRegion($grArr[1])
            ->setCountry($grArr[2]);
        return $city;
    }

    protected function getUsers()
    {
        $users = array();
        $f = fopen(self::DATA_PATH . "users.txt", "r");
        while (!feof($f)) {
            $rowStr = fgets($f);
            $rowArr = explode(";", $rowStr);
            if (count($rowArr) == 3) {
                $user = (new User())
                    ->setUsername($rowArr[0])
                    ->setPassword($rowArr[1])
                    ->setRights(substr($rowArr[2], 0, 9));
                $users[] = $user;
            }
        }
        fclose($f);
        return $users;

    }

    protected function getUser($id)
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user->getUserName() == $id) {
                return $user;
            }
        }
        return false;
    }

    protected function setRegion(Region $region)
    {   
        $f = fopen(self::DATA_PATH. $region->getCityId(). "/" . $region->getId(), "w");
        $grArr = array($region->getName(), $region->getSquare(), $region->getPopulation());
        $grStr = implode(";", $grArr);
        fwrite($f, $grStr);
        fclose($f);
    }
    protected  function  delRegion(Region $region)
    {
        unlink(self::DATA_PATH . $region->getCityId(). "/" . $region->getId());

    }
    protected function insRegion(Region $region)
    {
        $path = self::DATA_PATH . $region->getCityId();
        $conts = scandir($path);
        $i = 0;
        foreach ($conts as $node){
            if(preg_match(self::REGION_FILE_TEMPLATE, $node)){
                $last_file = $node;
            }
        }
        $file_index = (String)(((int)substr($last_file, -6, 2)) + 1);
        if(strlen($file_index)== 1){
            $file_index = "0" . $file_index;
        }

        $newFileName = "region-" . $file_index . ".txt";

        $region->setId($newFileName);
        $this->setRegion($region);

    }
    protected function setCity(City $city)
    {
       $f = fopen(self::DATA_PATH. $city->getId(). "/city.txt", "w");
       $grArr = array($city->getCity(), $city->getRegion(), $city->getCountry());
       $grStr = implode(";", $grArr);
       fwrite($f, $grStr);
       fclose($f);
    }
    protected function setUser(User $user)
    {
       $users = $this->getUsers();
       $found = false;
        foreach ($users as $key=> $oneUser) {
            if($user->getUsername() == $oneUser->getUsername()){
                $found = true;
                break;
            }
       }
        if($found){
            $users[$key] = $user;
            $f = fopen(self::DATA_PATH . "users.txt","w");
            foreach ($users as $oneUser){
                $grArr = array($oneUser->getUsername(),$oneUser->getPassword(),$oneUser->getRights(). PHP_EOL);//
                $grStr = implode(";", $grArr);
                fwrite($f, $grStr);
            }
            fclose($f);
        }
    }
    protected function delCity($cityId)
    {
        $dirName = self::DATA_PATH . $cityId;
        $conts = scandir($dirName);
        $i=0;
        foreach ($conts as $node) {
            @unlink($dirName. "/" .$node);
        }
        @rmdir($dirName);
    }
    protected function insCity()
    {
       $path=self::DATA_PATH;
       $conts =scandir($path);
       foreach ($conts as $node){
           if(preg_match(self::CITY_FILE_TEMPLATE, $node)){
               $last_city = $node;
           }
       }
       $city_index = (String)(((int)substr($last_city, -1,2))+1);
       if(strlen($city_index)==1){
           $city_index="0" . $city_index;

       }
       $newCityName = "city-" . $city_index;
       mkdir($path. $newCityName);
       $f = fopen($path. $newCityName . "/city.txt", "w");
       fwrite($f, "New; ; ");
       fclose($f);
    }
}
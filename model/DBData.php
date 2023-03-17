<?php

namespace Model;

class DBData extends Data
{
    private $db;
    public function __construct(MySQLdb $db)
    {
        $this->db = $db;
        $this->db->connect();
    }

    protected function getRegions($cityId)
    {
        $Regions = array();
        if ($region_arr = $this->db->getArrFromQuery("select id, name, square, city_id, population from regions where city_id=" . $cityId)) {
            foreach ($region_arr as $region_row) {
                $region = (new Region())
                ->setId($region_row['id'])
                ->setName($region_row['name'])
                ->setSquare($region_row['square'])
                ->setCityId($region_row['city_id'])
                ->setPopulation($region_row['population']);
                $Regions[] = $region;
            };
        }
        return $Regions;
    }
    protected function getRegion($cityId, $id)
    {
        $region = new Region();
        if ($region_arr = $this->db->getArrFromQuery("select id, name, square, city_id, population from regions where id=" . $id)) {
            if (count($region_arr) > 0) {
                $region_row = $region_arr[0];
                $region
                    ->setId($region_row['id'])
                    ->setName($region_row['name'])
                    ->setSquare($region_row['square'])
                    ->setCityId($region_row['city_id'])
                    ->setPopulation($region_row['population']);
            }
        }
        return $region;
    }
    protected function getCities()
    {
        $cities = array();

        if ($city_arr = $this->db->getArrFromQuery("select id, city, region, country from cities")) {
            foreach ($city_arr as $city_row) {
                $city = (new City());
                $city->setId($city_row['id']);
                $city->setCity($city_row['city']);
                $city->setRegion($city_row['region']);
                $city->setCountry($city_row['country']);
                $cities[] = $city;
            }
        }
        return $cities;
    }
    protected function getCity($id)
    {
        $city = new City();
        if ($city_arr = $this->db->getArrFromQuery("select id, city, region, country from cities where id=" . $id)) {
            if (count($city_arr) > 0) {
                $city_row = $city_arr[0];

                $city->setId($city_row['id']);
                $city->setCity($city_row['city']);
                $city->setRegion($city_row['region']);
                $city->setCountry($city_row['country']);
            }
        }
        return $city;
    }
    protected function getUsers()
    {
        $users = array();
        if ($user_arr = $this->db->getArrFromQuery("select id, username, passwd, rights from users")) {
            foreach ($user_arr as $user_row) {
                $user = (new User())
                    ->setUsername($user_row['username'])
                    ->setPassword($user_row['passwd'])
                    ->setRights($user_row['rights']);
                $users[] = $user;
            }
        }
        return $users;
    }
    protected function getUser($id)
    {
        $user =  new User();
        if ($users = $this->db->getArrFromQuery("select id, username, passwd, rights from users where username='" . $id . "'")) {
            if (count($users) > 0) {
                $user_row = $users[0];
                $user
                    ->setUsername($user_row['username'])
                    ->setPassword($user_row['passwd'])
                    ->setRights($user_row['rights']);
            }
        }
        return $user;
    }
    protected function setRegion(Region $region)
    {
        $sql = "update regions set name='" . $region->getName() . "', square='" . $region->getSquare() . "', population='" . $region->getPopulation() . "' , city_id ='" . $region->getCityId() . "' where id=" . $region->getId();
        $this->db->runQuery($sql);
        
    }
    protected function delRegion(Region $region)
    {
        $sql = "delete from regions where id = " . $region->getId();
        $this->db->runQuery($sql);
    }
    protected function insRegion(Region $region)
    {
       
        $sql = "insert into regions(name, square,  population, city_id) values('" . $region->getName() . "', '" . $region->getSquare() . "','" . $region->getPopulation() . "','" . $region->getCityId() . "')";
        $this->db->runQuery($sql); 
       
    }
    protected function setCity(City $city)
    {
        $sql = "update cities set city= '" . $city->getCity() . "', region='" . $city->getRegion() . "', country='" . $city->getCountry() . "'where id=" . $city->getId();
        $this->db->runQuery($sql);
    }
    protected function setUser(User $user)
    {

        $sql = "update users set rights='" .$user->getRights() . "', passwd='".$user->getPassword(). "'where username='" .$user->getUsername()."'";
        $this->db->runQuery($sql);
 
    }
    protected function delCity($cityId)
    {
        $sql = "delete from cities where id = " . $cityId;
        $this->db->runQuery($sql);
    }
    protected function insCity()
    {
        $sql = "insert into cities(city, region, country) values('new','','')";
        $this->db->runQuery($sql);
    }
}

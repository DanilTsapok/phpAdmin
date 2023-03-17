<?php 
require_once '../controller/autorun.php';
require_once '../data/config.php';

$db = new \Model\MySQLdb();
$db->connect();
$db->runQuery("delete from regions");
$db->runQuery("delete from cities");
$db->runQuery("delete from users");

$fileModel = \Model\Data::makeModel(\Model\Data::FILE);
$fileModel->setCurrentUser('admin');
$users = $fileModel->readUsers();
foreach($users as $user){
    
    $db->runQuery("insert into users(username, passwd, rights) values('" . $user->getUsername() ."','". $user->getPassword() . "','" . $user->getRights() . "')");
}

$dbModel = \Model\Data::makeModel(\Model\Data::DB);
$dbModel->setCurrentUser('admin');

$cities = $fileModel->readCities();
foreach ($cities as $city){
    
    $sql ="insert into cities (`city`, region, country) values('" . $city->getCity() . "','" .$city->getRegion(). "','" . $city->getCountry() . "')";
    $db->runQuery($sql);

    $res = $db->getArrFromQuery('select max(id) id from cities');
    $city_id = $res[0]['id'];
    $regions = $fileModel->readRegions($city->getId());
    foreach ($regions as $region){
        $region->setCityId($city_id);
       
        $dbModel->addRegion($region);
    }
}

$db->disconnect();
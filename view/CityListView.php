<?php

namespace View;

abstract class CityListView{
    const  SIMPLEVIEW = 0;
    const BOOTSTRAPVIEW = 1;
    private $user;
        
    public function setCurrentUser(\Model\User $user) {
        $this->user = $user;
    }
    public function checkRight($object, $right) {
        return $this->user->checkRight($object, $right);
    }

    public abstract function showMainForm($cities, \Model\City $city, $regions);
    public abstract function showCityEditForm(\Model\City $city);
    public abstract function showRegionEditForm(\Model\Region $region);
    public abstract function showRegionCreateForm();
    public abstract function showLoginForm();
    public abstract function showAdminForm($users);
    public abstract function showUserEditForm(\Model\User $user);

    public static function makeView($type) {
        if($type == self::SIMPLEVIEW) {
            return new MyView();
        } elseif ($type == self::BOOTSTRAPVIEW) {
            return new BootstrapView();
        }
        return new MyView();
    }
}


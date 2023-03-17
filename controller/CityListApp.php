<?php 

namespace Controller;

use Model\Data;
use Model\Region;
use View\CityListView;

class CityListApp {
    private $model;
    private $view;

    public function __construct($modelType, $viewType){
        session_start();
        $this->model = Data::makeModel($modelType);
        $this->view = CityListView::makeView($viewType);
    }

   public function checkAuth(){
        if ($_SESSION['user']){
           $this->model->setCurrentUser($_SESSION['user']);
           $this->view->setCurrentUser($this->model->getCurrentUser());
        } else{
            header('Location: ?action=login');
        } 
    }

    public function run() {
        if (!in_array($_GET['action'], array('login','checkLogin'))){
            $this->checkAuth();
        }
        if ($_GET['action']){
            switch($_GET['action']){
                case 'login':
                    $this->showLoginForm();
                    break;
                case 'checkLogin':
                    $this->checkLogin();
                    break;
                case 'logout':
                    $this->logout();
                    break;
                case 'create-city':
                    $this->createCity();
                    break;
                case 'edit-city-form':
                    $this->showEditCityForm();
                    break;
                case 'edit-city':
                    $this->editCity();
                    break;
                case 'delete-city':
                    $this->deleteCity();
                    break;
                case 'create-region-form':
                    $this->showCreateRegionForm();
                    break;
                case 'create-region':
                    $this->createRegion();
                    break;
                case 'edit-region-form':
                    $this->showEditRegionForm();
                    break;
                case 'edit-region':
                    $this->editRegion();
                    break;
                case 'delete-region':
                    $this->deleteRegion();
                    break;
                case 'admin':
                    $this->adminUsers();
                    break;
                case 'edit-user-form':
                    $this->showEditUserForm();
                    break;
                case 'edit-user':
                    $this->editUser();
                    break;
                default:
                    $this->showMainForm();
            }
        }else{
            $this->showMainForm();
            }
        }

        private function showLoginForm(){
            $this->view->showLoginForm();
        }
        private function checkLogin(){
            if($user = $this->model->readUser($_POST['username'])){
                if($user->checkPassWord($_POST['password'])){
                    session_start();
                    $_SESSION['user'] = $user->getUsername();
                    header('Location: index.php');
                }
            }
        }
        private function logout() {
            unset($_SESSION['user']);
            header('Location: ?action=login');
        }
        private function showMainForm(){
            $cities= array();
            if($this->model->checkRight('city', 'view')){
                $cities = $this->model->readCities();
            }
            $city = new \Model\City();
            if($_GET['city'] && $this->model->checkRight('city', 'view')){
                $city = $this->model->readCity($_GET['city']);
            }
            $regions = array();
            if($_GET['city'] && $this->model->checkRight('region','view')){
                $regions = $this->model->readRegions($_GET['city']);
            }
        
            $this->view->showMainForm($cities, $city, $regions);
        }

        private function createCity() {
            if(!$this->model->addCity()){
                die($this->model->getError());
            }else{
                header('Location: index.php');
            }      
        }
        
        private function showEditCityForm(){
            if (!$city = $this->model->readCity($_GET['city'])){
                die($this->model->getError());
            }
            $this->view->showCityEditForm($city);
          

            
        }
        private function editCity(){
            if(!$this->model->writeCity((new \Model\City())
                ->setId($_GET['city'])
                ->setCity($_POST['city'])
                ->setRegion($_POST['region'])
                ->setCountry($_POST['country'])
        )) {
            die($this->model->getError());
        } 
        else {
            header('Location: index.php?city=' . $_GET['city']);
        }
        }
        private function deleteCity(){
            if (!$this->model->removeCity($_GET['city'])){
                die($this->model->getError());
            }
            else {
                header('Location: index.php');
            }
           
        }
    
        private function showEditRegionForm(){
          
            $region = $this->model->readRegion($_GET['city'], $_GET['file']);
            $this->view->showRegionEditForm($region);
        }
        private function editRegion(){
            
            $region= (new \Model\Region())
            ->setId($_GET['file'])
            ->setCityId($_GET['city'])
            ->setName($_POST['region_name'])
            ->setSquare($_POST['square'])
            ->setPopulation($_POST['population']);
          
            if(!$this->model->writeRegion($region)){
                die($this->model->getError());
            }
          
            else{
                header('Location: index.php?city=' . $_GET['city']);
            } 
            
        }
        private function showCreateRegionForm(){
            $this->view->showRegionCreateForm();
        }
        private function createRegion(){
            $region = (new \Model\Region())
            ->setCityId($_GET['city'])
            ->setName($_POST['region_name'])
            ->setSquare($_POST['square'])
            ->setPopulation($_POST['population']);
        
        if (!$this->model->addRegion($region)){
            die($this->model->getError());
        } 
        else{
            header('Location: index.php?city='. $_GET['city']);
        }
        }
        private function deleteRegion(){
            $region = (new \Model\Region())->setId($_GET['file'])->setCityId($_GET['city']);
        if (!$this->model->removeRegion($region)){
            die($this->model->getError());
        } 
        else{
            header('Location: index.php?city=' . $_GET['city']);
        }
        }
        private function adminUsers(){
            $users = $this->model->readUsers();
            $this->view->showAdminForm($users);
        }
        private function showEditUserForm(){
            if(!$user= $this->model->readUser($_GET['username'])){
                die($this->model->getError());
            }
            $this->view->showUserEditForm($user);
        }
        private function editUser() {
            $rights = "";
            for($i=0; $i<9; $i++){
                if($_POST['right' . $i]){
                    $rights .= "1";
                }else{
                    $rights .= "0";
                }
            }
            $user = (new \Model\User())
                ->setUsername($_POST['user_name'])
                ->setPassword($_POST['user_pwd'])
                ->setRights($rights);
            if(!$this->model->writeUser($user)){
                die($this->model->getError());
            } 
            else{
                header('Location: ?action=admin ');
            }
        }
    }   
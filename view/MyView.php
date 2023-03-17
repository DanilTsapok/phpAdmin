<?php 

namespace View;


class MyView extends CityListView {
    private function showCities ($cities){
        ?>
        <form name='city-form' method='get'>
            <label for="city">Місто</label>
            <select name="city">
                <option value=""></option>
                <?php
                foreach($cities as $curcity){
                    echo "<option " . (($curcity->getId() == $_GET['city'])?"selected":"") . "value=" . $curcity->getId() . ">" . $curcity->getCity(). "</option>";
                }
                ?>
            </select>
            <input type="submit" value="ok">
            <?php if($this->checkRight('city', 'create')):?>
                <a href="?action=create-city">Додати групу</a>
            <?php endif;?>
        </form>
        <?php
    }

    private function showCity(\Model\City $city){
    ?>
        <h1>Місто:<span class="city"><?php echo $city->getCity();?></span></h1>
        <h3>Область: <span class="region" ><?php echo $city->getRegion();?></span></h3>
        <h3>Країна: <span class="country"><?php echo $city->getCountry();?></span></h3>
        <div class = "control">
        <?php  if($this->checkRight('city','edit')):?>
            <a href="?action=edit-city-form&city=<?php echo $_GET['city'];?>">Редагувати місто</a>
            <?php endif;?>
            <?php  if($this->checkRight('city','delete')):?>
                <a href="?action=delete-city&city=<?php echo $_GET['city'];?>">Видалити місто</a>
            <?php endif;?>
        </div>
        <?php
    }
    private function showRegions($regions){
    ?>
         <section>
        <?php
            if($_GET['city']):?>
            <?php  if($this->checkRight('region','create')):?>
            <div class="control">
                <a href="?action=create-region-form&city=<?php echo $_GET['city'];?>">Додати район</a>
            </div>
        <?php endif;?>
        <form method="POST"name="region-filter">
            Фільтрування за назвою району<input type="text" name="region-name-filter" value=<?php echo $_POST['region-name-filter'];?>>
            <input type="submit"value="Фільтрувати">
        </form>
         <table>
             <thead>
                 <tr>
                    <th>Назва району</th>
                    <th>Площа км2</th>
                    <th>Кількість населення</th>
                    <th></th>
                </tr>
             </thead>
             <tbody>
                <?php foreach($regions as $key => $region):?>
                    <?php
                    if(!$_POST['region-name-filter'] || stristr($region->getName(),$_POST['region-name-filter'])):
                        ?>
                    <?php
                    $row_class ="row";
                    if($region == "Корабельний район"){
                        $row_class='korabelniy';
                    }    
                    if($region == "Заводський район"){
                        $row_class='zavodskiy';
                    } 
                    if($region == "Інгульський район"){
                        $row_class='ingulskiy';
                    } 
                    if($region == "Центральний район"){
                        $row_class='centralniy';
                    } 
                    ?>

                <tr class=<?= $row_class?>>
                    <td><?php echo $region->getName();?></td>
                    <td><?php echo $region->getSquare();?></td>
                    <td><?php echo $region->getPopulation();?></td>
                    <td>
                        <?php  if($this->checkRight('region','edit')):?>
                        <a href="?action=edit-region-form&city=<?php echo $_GET['city'];?>&file=<?php echo $region->getId()?>">Редагувати</a>
                        <?php endif;?>
                        <?php  if($this->checkRight('region','edit')):?>
                        <a href="?action=delete-region&city=<?php echo $_GET['city'];?>&file=<?php echo $region->getId()?>">Видалити</a>
                        <?php endif;?>
                    </td>
                </tr>
                <?php endif;?>
                <?php endforeach;?>
             </tbody>
         </table>
          <?php endif; ?>
    </section>
    <?php
    }
public function showMainForm($cities, \Model\City $city, $regions) {
       ?> 
    <!DOCTYPE html>
    <html>
        <head>
            <title>Список міст</title>
            <link rel="stylesheet" type="text/css" href="css/main-style.css"> 
            <link rel="stylesheet" type="text/css" href="css/city-choose-style.css">
        </head>
        <body>
     <header>
        <div class="user-info">
            <span>Hello <?php echo $_SESSION['user'];?>!</span>
            <?php  if($this->checkRight('user','admin')):?>
            <a href="?action=admin">Адміністрування</a>
            <?php endif;?>
            <a href="?action=logout">Logout</a>
            
        </div>
        <?php  if($this->checkRight('city','view')){
            $this->showCities($cities);
            if ($_GET['city']){
                $this->showCity($city);
            }
        }  
        ?>
     </header>
     <?php
        if($this->checkRight('region','view')){
            $this->showRegions($regions);
        }
     ?>
    
    </body>    
    </html>
    <?php
    }
   
     public function showLoginForm() {
  ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login-style.css">
    <title>Логін</title>
</head>
<body>
        <form method="POST" action="?action=checkLogin">
            <p>
                <input type="text" name="username" placeholder="username" >
            </p>
            <p>
                <input type="password" name="password" placeholder="password">
            </p>
            <p>
                <input type="submit" value="Login">
            </p>
        </form>
</body>
</html>
<?php
}
public  function showCityEditForm(\Model\City $city){
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати місто</title>
    <link rel="stylesheet" href="css/edit-city-form-style.css">
</head>
<body>
    <a href="index.php?city=<?php echo $_GET['city'];?>">На головну</a>
    <form name ="edit-city"method="Post" action="?action=edit-city&city=<?php echo $_GET['city']?>">
    <div>
        <label for="city">Місто:</label>
        <input type="text"name="city" value="<?php echo $city->getCity();?>">
    </div>
    <div>
         <label for="region">Область:</label>
        <input type="text"name="region" value="<?php echo $city->getRegion();?>">
    </div>
    <div>
        <label for="country">Країна</label>
        <input type="text"name="country" value="<?php echo $city->getCountry();?>">
    </div>
    <div>
        <input type="submit" name ="ok"value="Змінити">
    </div>
    
    </form>
</body>
</html>
    <?php
}
public  function showRegionEditForm(\Model\Region $region){
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/edit-region-form-style.css">
    <title>Редагувати район</title>
</head>
<body>
    <a href="index.php?city=<?php echo $_GET['city'];?>">На головну</a>
    <form method="POST"name="edit-region" action="?action=edit-region&file=<?php echo $_GET['file'];?>&city=<?php echo $_GET['city'];?>">
        <div>
            <label for="region_name">Назва району:</label>
            <input type="text" name="region_name" value="<?php echo $region->getName()?>">
        </div>
        <div>
            <label for="square">Площа км2:</label>
            <input type="text" name="square" value="<?php echo $region->getSquare()?>">
        </div>
        <div>
            <label for="population">Кількість населення</label>
            <input type="text" name="population" value="<?php echo $region->getPopulation()?>">
        </div>
        <div>
            <input type="submit" value="Змінити" name="ok">
        </div>
    </form>
</body>
</html>
    <?php
}
public  function showRegionCreateForm(){
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/edit-region-form-style.css">
    <title>Додати район</title>
</head>
<body>
    <a href="?city=<?php echo $_GET['city'];?>">На головну</a>
    <form method="POST"name="edit-region" action="?action=create-region&city=<?php echo $_GET['city'];?>">
        <div>
            <label for="region_name">Назва району:</label>
            <input type="text" name="region_name" >
        </div>
        <div>
            <label for="square">Площа км2:</label>
            <input type="text" name="square" >
        </div>
        <div>
            <label for="population">Кількість населення</label>
            <input type="text" name="population" >
        </div>
        <div>
            <input type="submit" value="Додати" name="ok">
        </div>
    </form>
</body>
</html>
    <?php
}

public  function showAdminForm($users){
    ?>
    <!DOCTYPE html>
<html>
    <head>
        <title>Адміністрування</title>

    </head>
    <section>
        <body>
        <header>
            <a href="index.php">На головну</a>
            <h1>Адміністрування користувачів</h1>
            <link rel="stylesheet" href="css/main-style.css">
        </header>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Користувач</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user):?>
                    <?php if($user->getUsername() != $_SESSION['user']&& $user->getUsername()  != 'admin' && trim($user->getUsername()) !=''):?>
                    <tr>
                        <td><a href="?action=edit-user-form&username=<?php echo $user->getUsername();?>"><?php echo $user->getUsername() ;?></a></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach ;?>
                </tbody>
            </table>
        </section>
    </body>
    </section>
</html>

    <?php
}
public  function showUserEditForm(\Model\User $user){
 ?>
 <!DOCTYPE html>
<html>
    <head>
        <title>Редагування користувача</title>
        <link rel="stylesheet" href="/css/admin-style.css">
    </head>
<body>
    <a href="?action=admin">До списку користувачів</a>
    <form method="post" name="edit-user" action="?action=edit-user&user=<?php echo $_GET['user']?>">
        <div class="tbl">
            <div>
                <label for="user_name">Username:</label>
                <input readonly type="text" name="user_name" value="<?php echo $user->getUsername();?>">
            </div>
            <div>
                <label for="user_pwd">Password:</label>
                <input readonly type="text" name="user_pwd" value="<?php echo $user->getPassword();?>">
            </div>
        </div>
        <div><p>Місто:</p>
            <input type="checkbox"<?php echo ("1" == $user->getRight(0))?"checked":""; ?> name="right0" value="1"<span>перегляд</span>
            <input type="checkbox"<?php echo ("1" == $user->getRight(1))?"checked":""; ?> name="right1" value="1"<span>створення</span>
            <input type="checkbox"<?php echo ("1" == $user->getRight(2))?"checked":""; ?> name="right2" value="1"<span>редагування</span>
            <input type="checkbox"<?php echo ("1" == $user->getRight(3))?"checked":""; ?> name="right3" value="1"<span>видалення</span>
        </div>
        <div><p>Район:</p>
            <input type="checkbox"<?php echo ("1" == $user->getRight(4))?"checked":""; ?> name="right4" value="1"<span>перегляд</span>
            <input type="checkbox"<?php echo ("1" == $user->getRight(5))?"checked":""; ?> name="right5" value="1"<span>створення</span>
            <input type="checkbox"<?php echo ("1" == $user->getRight(6))?"checked":""; ?> name="right6" value="1"<span>редагування</span>
            <input type="checkbox"<?php echo ("1" == $user->getRight(7))?"checked":""; ?> name="right7" value="1"<span>видалення</span>
        </div>
        <div><p>Користувачі:</p>
            <input type="checkbox"<?php echo ("1" == $user->getRight(8))?"checked":""; ?> name="right8" value="1"<span>перегляд</span>
        </div>
        <div><input type="submit" name="ok" value="змінити"></div>
    </form>
</body>
</html>

 <?php 
}
}


    

    

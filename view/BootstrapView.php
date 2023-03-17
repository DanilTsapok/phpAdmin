<?php

namespace View;


class BootstrapView extends CityListView
{
    const ASSETS_FOLDER = 'view/bootstrap-view/';

    public function showUserInfo()
    {
?>
        <div class="container user-info">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-2 col-md-8 offset-md-5 text-center lead">
                    <span>Hello <?php echo $_SESSION['user']; ?> ! </span>
                    <?php if ($this->checkRight('user', 'admin')) : ?>
                        <a class="btn btn-primary" href="?action=admin">Адміністрування</a>
                    <?php endif; ?>
                    <a class="btn btn-info" href="?action=logout">Logout</a>
                </div>
            </div>
        </div>
    <?php
    }
    public function showCities($cities)
    {
    ?>
        <div class="container city-list">
            <div class="row">
                <form name="city-form" method="get" class="offset-2 col-8 offset-sm-3 col-sm-6">
                    <div class="form-city">
                        <label for="city"> Місто:</label>
                        <select name="city" class="form-control" onchange="document.forms['city-form'].submit();">
                            <option value=""></option>
                            <?php
                            foreach ($cities as $curcity) {
                                echo "<option " . (($curcity->getId() == $_GET['city']) ?
                                    "selected" : "") . "value=" . $curcity->getId() . ">" . $curcity->getCity() . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
                <?php if ($this->checkRight('city', 'create')) : ?>
                    <div class="col-12  city-list text-center">
                        <a class='btn btn-success' href="?action=create-city">Додати Місто</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    public function showCity(\Model\City $city)
    {
    ?>
        <div class="container city-info">
            <div class="row text-center">
                <h1 class="col-12">Місто:<span class="text-primary"><?php echo $city->getCity(); ?></span></h1>
                <h3 class="col-12 col-md-5 offset-md-1">Область: <span class="text-danger"><?php echo $city->getRegion(); ?></span></h3>
                <h3 class="col-12 col-md-5">Країна: <span class="text-success"><?php echo $city->getCountry(); ?></span></h3>
                <div class="city-control col-12">
                    <?php if ($this->checkRight('city', 'edit')) : ?>
                        <a class="btn btn-primary" href="?action=edit-city-form&city=<?php echo $_GET['city']; ?>">Редагувати місто</a>
                    <?php endif; ?>
                    <?php if ($this->checkRight('city', 'delete')) : ?>
                        <a class="btn btn-danger" href="?action=delete-city&city=<?php echo $_GET['city']; ?>">Видалити місто</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php
    }
    public function showRegions($regions)
    {
    ?>
        <section class="container regions">
            <div class="row text-center">
                <?php if ($this->checkRight('region', 'create')) : ?>
                    <div class="col-12 col-md-3 text-left add-region">
                        <a class="btn btn-success" href="?action=create-region-form&city=<?php echo $_GET['city']; ?>">Додати район</a>
                    </div>
                <?php endif; ?>
                <div class="col-12 col-md-8">
                    <form name='regions-filter' method='post'>
                        <div class="row">
                            <div class="col-5">
                                <label for="region_name_filter">Фільтрувати по району</label>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type="text" name="region-name-filter" value='<?php echo $_POST['region-name-filter']; ?>'>
                            </div>
                            <div class="col-3">
                                <input type="submit" value="Фільтрувати" class="btn btn-info">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row text-center table-regions">
                <table class="col-lg-12  table">
                    <thead>
                        <tr>
                            <th>Назва району</th>
                            <th>Площа км2</th>
                            <th>Кількість населення</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($regions as $key => $region) : ?>
                            <?php
                            if (!$_POST['region-name-filter'] || stristr($region->getName(), $_POST['region-name-filter'])) :
                            ?>
                                <?php
                              
                                if ($region == "Корабельний район") {
                                    $row_class = 'korabelniy';
                                }
                                if ($region == "Заводський район") {
                                    $row_class = 'zavodskiy';
                                }
                                if ($region == "Інгульський район") {
                                    $row_class = 'ingulskiy';
                                }
                                if ($region == "Центральний район") {
                                    $row_class = 'centralniy';
                                }
                                ?>

                                    <tr class=<?= $row_class ?>>
                                    <td><?php echo $region->getName(); ?></td>
                                    <td><?php echo $region->getSquare(); ?></td>
                                    <td><?php echo $region->getPopulation(); ?></td>
                                    <td>
                                        <?php if ($this->checkRight('region', 'edit')) : ?>
                                            <a class="btn btn-primary btn-sm" href="?action=edit-region-form&city=<?php echo $_GET['city']; ?>&file=<?php echo $region->getId() ?>">Редагувати</a>
                                        <?php endif; ?>
                                        <?php if ($this->checkRight('region', 'edit')) : ?>
                                            <a class="btn btn-danger btn-sm" href="?action=delete-region&city=<?php echo $_GET['city']; ?>&file=<?php echo $region->getId() ?>">Видалити</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php
    }
    public function showLoginForm()
    {
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="<?php echo self::ASSETS_FOLDER; ?>css/login.css">
            <link rel="stylesheet" href="<?php echo self::ASSETS_FOLDER; ?>/css/bootstrap.min.css">
            <title>Аутентифіція</title>
        </head>

        <body>
            <form method="POST" action="?action=checkLogin">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-sm-6 col-md-4 col-lg-3 offset-sm-3 offset-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </div>
            </form>
        </body>

        </html>
    <?php
    }
    public  function showMainForm($cities, \Model\City $city, $regions)
    {
    ?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="UTF-8">
            <title>Список міст</title>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>/css/main.css">
        </head>

        <body>
            <header>
                <?php
                $this->showUserInfo();
                if ($this->checkRight('city', 'view')) {
                    $this->showCities($cities);
                    if ($_GET['city']) {
                        $this->showCity($city);
                    }
                } ?>
            </header>
            <?php
            if ($this->checkRight('region', 'view')) {
                $this->showRegions($regions);
            }
            ?>
        </body>

        </html>
    <?php
    }
    public  function showCityEditForm(\Model\City $city)
    {
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Редагувати місто</title>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>/css/main.css">
        </head>

        <body>
            <div class="container">
                <div class="row">
                    <div class="col-12 ">
                        <form name="edit-city" method="Post" action="?action=edit-city&city=<?php echo $_GET['city']; ?>">
                            <div class="form-city">
                                <label for="city">Місто:</label>
                                <input сlass="form-control " type="text" name="city" value="<?php echo $city->getCity(); ?>">
                            </div>
                            <div class="form-city">
                                <label for="region">Область:</label>
                                <input сlass="form-control " type="text" name="region" value="<?php echo $city->getRegion(); ?>">
                            </div>
                            <div class="form-city">
                                <label for="country">Країна:</label>
                                <input сlass="form-control " type="text" name="country" value="<?php echo $city->getCountry(); ?>">
                            </div>
                            <div class="edit-region">
                                <a class="btn btn-info " href="index.php?city=<?php echo $_GET['city']; ?>">На головну</a>
                                <input type="submit" class="btn btn-success float-right" value="Змінити" name="ok">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </body>

        </html>
    <?php
    }
    public  function showRegionEditForm(\Model\Region $region)
    {
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>/css/main.css">
            <title>Редагувати район</title>
        </head>

        <body>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-5 col-md-1 col-lg-3">
                        <form method="POST" name="edit-region" action="?action=edit-region&file=<?php echo $_GET['file']; ?>&city=<?php echo $_GET['city'];?>">
                            <div>
                                <label for="region_name">Назва району:</label>
                                <input type="text" name="region_name" value="<?php echo $region->getName() ?>">
                            </div>
                            <div>
                                <label for="square">Площа км2:</label>
                                <input type="text" name="square" value="<?php echo $region->getSquare() ?>">
                            </div>
                            <div>
                                <label for="population">Кількість населення</label>
                                <input type="text" name="population" value="<?php echo $region->getPopulation() ?>">
                            </div>
                            <div class="edit-region">
                                <a class="btn btn-info" href="index.php?city=<?php echo $_GET['city']; ?>">На головну</a>
                                <input type="submit" class="btn btn-success float-right" value="Змінити" name="ok">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </body>

        </html>
    <?php
    }
    public  function showRegionCreateForm()
    {
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>/css/main.css">
            <title>Додати район</title>
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-5 col-md-1 col-lg-3">
                        <form method="POST" name="edit-region" action="?action=create-region&city=<?php echo $_GET['city']; ?>">
                            <div>
                                <label for="region_name">Назва району:</label>
                                <input type="text" name="region_name">
                            </div>
                            <div>
                                <label for="square">Площа км2:</label>
                                <input type="text" name="square">
                            </div>
                            <div>
                                <label for="population">Кількість населення</label>
                                <input type="text" name="population">
                            </div>
                            <div class="edit-region">
                                <a class="btn btn-info" href="?city=<?php echo $_GET['city']; ?>">На головну</a>
                                <input class="btn btn-success float-right" type="submit" value="Додати" name="ok">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </body>
        </html>
<?php
    }

    public  function showAdminForm($users)
    { 
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
    public  function showUserEditForm(\Model\User $user)
    {
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

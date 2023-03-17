<?php
   require 'data/config.php';
   require 'controller/autorun.php';
   $controller = new \Controller\CityListApp(Config::$modelType, Config::$viewType);
   $controller->run();


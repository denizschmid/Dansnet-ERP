<?php

    require_once "./bower_components/ClassLoader/ClassLoader.php";
    new ClassLoader("bower_components/");

    $date = new DateTime();
    $data = new DataAccessObject("test");
    var_dump($date);
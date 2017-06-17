<?php

    include('config/config.php');

    if(DEBUG) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        //$nuraghe = new nuragheClass();
        //$ll = $nuraghe->getElByText('Abbasanta');
        //var_dump($ll);
        $bot = new botClass();
        $bot->go();
    }else{
        $bot = new botClass();
        $bot->go();
    }
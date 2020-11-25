<?php

//var_dump
function p($data = null)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

//afficher données
function d($data)
{
    p($data);
    die;
}

/**
 * @return PDO
 */

function getBdd(){
    try {
        $bdd = new PDO(
            'mysql:host=localhost;dbname=fichiers;charset=utf8',
            'root',
            '',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
        );
        return $bdd;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}


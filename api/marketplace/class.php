<?php

/* 
 * Madha Web - Desenvolvimento.
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 */

require '../config/main.php';

/* @var $header type */
header("Content-type: Application/json");

$obj = new Marketplace();
$market = null;
/* @var $_GET type */
$option = null;

if(isset($_GET['opt']) || !empty($_GET['opt']) || !empty($_GET['market']) || isset($_GET['market']) != '' ) {
    $option = $_GET['opt'];
    switch ($option) {
        case "add":
            return $obj->addAccounts();
            break;
        default:
            echo json_encode(
            array('msg' => 'sem parâmetros definidos')
            );
            break;
    }
} else {
    return $obj->getAccounts();
}
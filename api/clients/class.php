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

$obj = new Clients();
$client = null;
/* @var $_GET type */
$option = null;

if(isset($_GET['opt']) || !empty($_GET['opt']) || !empty($_GET['client']) || isset($_GET['client']) != '' ) {
    $option = $_GET['opt'];
    switch ($option) {
        case "view":
            $client = $_GET['client'];
            return $obj->viewClient($client);
            break;
        case "add":
            return $obj->addClient();
            break;
        case "update":
            $client = $_GET['client'];
            return $obj->updateClient($client);
            break;
        case "search":
            $client = $_GET['client'];
            return $obj->searchClient($client);
            break;
        case "del":
            $client = $_GET['client'];
            return $obj->delClient($client);
            break;

        default:
            echo json_encode(
            array('msg' => 'sem parâmetros definidos')
            );
            break;
    }
} else {
    return $obj->getClient();
}
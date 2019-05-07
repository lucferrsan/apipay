<?php

/* 
 * Madha Web - Desenvolvimento.
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 */

require '../config/main.php';

header("Content-type: Application/json");

$obj = new Account();
$account = null;
$option = null;

if(isset($_GET['opt']) || !empty($_GET['opt']) || !empty($_GET['ID']) || isset($_GET['ID']) != ''){
    
    $option = $_GET['opt'];
    switch ($option)
    {
        case "view":
            $account = $_GET['ID'];
            return $obj->viewAccount($account);
            break;
        
        case "verification":
            $account = $_GET['ID'];
            return $obj->verAccount($account);
            break;
        default:
            echo json_encode(
                    array('msg' => 'sem parâmetros definidos!')
                    );
            break;
    }
}
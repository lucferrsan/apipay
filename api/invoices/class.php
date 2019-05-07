<?php

/* 
 * Madha Web - Desenvolvimento.
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 */
require '../config/main.php';

/* @var $header type */
header("Content-type: text/json");

$obj = new Invoices();
$invoice = null;
/* @var $_GET type */
$option = null;

if(isset($_GET['opt']) || !empty($_GET['opt']) || !empty($_GET['invoice']) || isset($_GET['invoice']) != '' ) {
    $option = $_GET['opt'];
    switch ($option) {
        case "view":
            $invoice = $_GET['invoice'];
            return $obj->viewInvoice($invoice);
            break;
        case "add":
            return $obj->addInvoice();
            break;
        case "update":
            $invoice = $_GET['invoice'];
            return $obj->updateInvoice($invoice);
            break;
        case "cancel":
            $invoice = $_GET['invoice'];
            return $obj->cancelInvoice($invoice);
            break;
        case "del":
            $invoice = $_GET['invoice'];
            return $obj->delInvoice($invoice);
            break;

        default:
            echo json_encode(
            array('msg' => 'sem parâmetros definidos')
            );
            break;
    }
} else {
    return $obj->getInvoices();
}


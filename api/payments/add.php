<?php

/* 
 * Madha Web - Desenvolvimento.
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 */
require '../config/main.php';

$obj = new Payments();

return $obj->addPayment();

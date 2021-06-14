<?php

/* 
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 * Arquivo: main.php
 */

// Iniciar sessão
// 
session_start();

//
//
if(isset($_GET['logged_in'])){
    if(isset($_SESSION['logged_in'])){
        if($_SESSION['logged_in'] == true){
            $logged_in = true;
        } else {
            $logged_in = false;
        }
    } else {
        $logged_in = false;
    }
    
    if($logged_in == true){
        echo "Voce está logado";
    } else {
        echo "Voce não está logado";
    }
} else {
    echo "Voce não pode acessar está área.";
}

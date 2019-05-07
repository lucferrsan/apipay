<?php

/* 
 * Madha Web - Desenvolvimento.
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 */

class Database {
    
    public function connect(){
        $PDO = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $PDO;
    }
    
    public function hash($str){
        return sha1(md5($str));
    }
    
    public function isLogged(){
        if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == true){
            return false;
        }
        return true;
    }
}
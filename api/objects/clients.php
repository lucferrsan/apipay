<?php

/* 
 * Madha Web - Desenvolvimento.
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 */

class LocalClients {
    
    //
    private $conn;
    private $table_name = "clients";
    
    //
    public $client_id;
    public $client_name;
    public $client_email;
    public $client_tel;
    public $client_description;
    
    //
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function read(){
        
        //
        $query = "SELECT ALL FROM" . $this->table_name;
        $statment = $this->conn->prepare($query);
        
        //
        $statment->execute();
    }
}
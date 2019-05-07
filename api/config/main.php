<?php

/* 
 * Madha Web - Desenvolvimento.
 * Api de Pagamentos Fpay | Iugu
 * Autor: Luciano Santos.
 * E-mail: lucferrsan@gmail.com.
 * Arquivo: main.php
 */

define('API_VERSION', 'v1');
define('COD_VERSION', '1.0.0');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
//LOCAL
define('DB_PASS', '');
define('DB_NAME', 'fpay');

include '../framework/iugu/lib/Iugu.php';
require '../config/database.php';


$api = null;
$ID = null;

$db = new Database();

$PDO = $db->connect();

$ID = $_GET['ID'];
$sql = "SELECT * FROM accounts WHERE account_id = '".$ID."';";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($users[0]['account_type'] != 'master'){
    $api = $users[0]['account_user_token'];
 
} else {
    $api = $users[0]['account_test_token'];
}

//Chave da API Iugu
//Live Key
//Iugu::setApiKey(" ");
//Test Key
Iugu::setApiKey($api);
//
//User Token ID: 
//Iugu::setApiKey(" ");
//
//Classe Clientes
class Clients extends Iugu_Customer {

    /*
     * Construtor
     */
    public function __construct() {

    }
    
    /*
     * @see getClient()
     * Método lista clientes
     */
    public function getClient(){
        
        $clients = Iugu_Customer::search()->results();
        
        if(count($clients) != 0){
            foreach ($clients as $value){              
                $items = array(
                    'id' => $value->id,
                    'name' => $value->name,
                    'email' => $value->email,
                    'notes' => $value->notes,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at
                );                
                echo json_encode($items);
            }     
        } else {
            $retorno = array('msg' => 'sem items');
            echo json_encode($retorno);
        }
    }
    
    /*
     * @see searchClient()
     * Método busca de clientes
     */
    public function searchClient($client){
        /* @var 
         * $client type
         */
        $clients = Iugu_Customer::fetch($client);
        
        $items = array(
            'id' => $clients->id,
            'email' => $clients->email,
            'name' => $clients->name,
            'notes' => $clients->notes,
            'created_at' => $clients->created_at,
            'updated_at' => $clients->updated_at,
            'custom_variables' => $clients->custom_variables
            
        );
        
        echo json_encode($items);
    }
    
    /*
     * @see viewClient()
     * Método detalhe de cliente
     */    
    public function viewClient($client){
        
        $clients = Iugu_Customer::fetch($client);
        
        $items = array(
            'id' => $clients->id,
            'email' => $clients->email,
            'name' => $clients->name,
            'notes' => $clients->notes,
            'created_at' => $clients->created_at,
            'updated_at' => $clients->updated_at,
            'custom_variables' => array($clients->custom_variables)
            
        );
        
        echo json_encode($items);
            
    }
       
    /*
     * @see getClient()
     * Método adicionar cliente
     */
    public function addClient(){
        
        $clients = Iugu_Customer::create(Array(
            'email' => $_GET['email'],
            'name' => $_GET['name'],
            'notes' => $_GET['notes']
        ));
        
        if($clients->id){
            $retorno = array('id' => $clients->id, 'name' => $clients->name, 'notes' => $clients->notes, 'status'=>'success');
        }else{
            $retorno = array('status'=>'error');
        }
        echo json_encode($retorno);

    }
    
    public function updateClient($client){
        
        $clients = Iugu_Customer::fetch($client);
        if(isset($_GET['email'])) { 
            $clients->email = $_GET['email'];
        }
        if(isset($_GET['name'])) {
            $clients->name = $_GET['name'];
        }
        if(isset($_GET['notes'])) {
            $clients->notes = $_GET['notes'];
        }
        $clients->save();
        
        if($clients->id){
            $retorno = array(
                'id' => $clients->id,
                'email' => $clients->email,
                'name' => $clients->name,
                'notes' => $clients->notes,
                'created_at' => $clients->created_at,
                'updated_at' => $clients->updated_at,
                'custom_variables' => array($clients->custom_variables),
                'update'=>'success');
        } else {
            $retorno = array('update'=>'error');
        }
        
        echo json_encode($retorno);
    }
    
    public function delClient($client) {
        
        $clients = Iugu_Customer::fetch($client);
        $clients->delete();
        if($clients->id) {
            $retorno = array(
                'id' => $clients->id,
                'email' => $clients->email,
                'name' => $clients->name,
                'notes' => $clients->notes,
                'created_at' => $clients->created_at,
                'updated_at' => $clients->updated_at,
                'custom_variables' => array($clients->custom_variables),
                'delete'=>'success'
                );
        } else {
            $retorno = array('delete'=>'error');
        }
        
        echo json_encode($retorno);
        
    }
}

/*
 * @see class 
 * Class Payments 
 */
class Invoices extends Iugu_Invoice {
    
    private $totalItens;
    private $item;
    private $id;
    private $invoices;
    private $incoive;
    private $page;
    private $fatura;
    public $header;

    
    /*
     * Função getInvoices
     * Lista todas as faturas
     */
    public function getInvoices() {

        $invoices = Iugu_Invoice::search()->results();

        if (count($invoices) != 0) {
            foreach ($invoices as $value){               
                $items = array([
                        'id' => $value->id,
                        'due_date' => $value->due_date,
                        'currency' => $value->currency,
                        'customer_id' => $value->customer_id,
                        'discount_cents' => $value->discount_cents,
                        'email' => $value->email,
                        'expiration_url' => $value->expiration_url,
                        'notification_url' => $value->notification_url,
                        'return_url' => $value->return_url,
                        'status' => $value->status,
                        'tax_cents' => $value->tax_cents,
                        'updated_at' => $value->updated_at,
                        'total_cents' => $value->total_cents,
                        'commission_cents' => $value->commission_cents,
                        'secure_id' => $value->secure_id,
                        'secure_url' => $value->secure_url,
                        'user_id' => $value->user_id,
                        'commission' => $value->commision,
                        'total' => $value->total,
                        'created_at' => $value->created_at,
                        'items' => $value->items,
                        'variables' => $value->variables,
                        'logs' => $value->logs
                    ]);
                echo json_encode($items);
            }

        } else {
            $retorno = array('msg' => 'sem items');
            echo json_encode($retorno);
        }

    }
    
    /*
     * @see viewInvoice()
     * Método detalha fatura
     */
    public function viewInvoice($invoice){
        
        $item = Iugu_Invoice::fetch($invoice);

        $items = array(
            'id '=> $item->id,
            'due_date' => $item->due_date,
            'currency' => $item->currency,
            'discount_cents' => $item->discount_cents,
            'email' => $item->email,
            'item_total_cents' => $item->item_total_cents,
            'notification_url' => $item->notification_url,
            'return_url' => $item->return_url,
            'status' => $item->status,
            'tax_cents' => $item->tax_cents,
            'updated_at' => $item->update_at,
            'total_cents' => $item->total_cents,
            'paid_at' => $item->paid_at,
            'comission_cents' => $item->comission_cents,
            'secure_id' => $item->secure_id,
            'secure_url' => $item->secure_url,
            'customer_id' => $item->customer_id,
            'user_id' => $item->user_id,
            'total' => $item->total,
            'taxes_paid' => $item->taxes_paid,
            'financial_return_date' => $item->financial_return_date,
            'commission' => $item->commission,
            'interest' => $item->interest,
            'discount' => $item->discount,
            'created_at' => $item->created_at,
            'refundable' => $item->refundable,
            'installments' => $item->installments,
            'financial_return_dates' => $item->financial_return_dates,
            'bank_slip' => $item->bank_slip,
            'items' => $item->items,
            'variables' => $item->variables,
            'custom_variables' => $item->custom_variables,
            'logs' => $item->logs
        );
        
        
        if($invoice == $item->id){
            echo json_encode($items);

        } else {
            print_r($item->errors);
        }

       

    }
    
    /*
     * @see addInvoice()
     * Método para criar fatura
     */
    public function addInvoice(){
        
        
        $invoice = Iugu_Invoice::create(Array(
            "email" => $_GET['email'],
            "due_date" => $_GET['due_date'],
            "items" => Array(
                Array(
                    'description' => $_GET['description'],
                    'quantity' => $_GET['quantity'],
                    'price_cents' => $_GET['price_cents']
                )
            ),
            'payer' => Array(
                'name' => $_GET['name'],
                'cpf_cnpj' => $_GET['cpf_cnpj'],
                'phone_prefix' => $_GET['phone_prefix'],
                'phone' => $_GET['phone'],
                'email' => $_GET['email'],
                'address' => Array(
                    'street' => $_GET['street'],
                    'number' => $_GET['number'],
                    'city' => $_GET['city'],
                    'state' => $_GET['state'],
                    'country' => $_GET['country'],
                    'zip_code' => $_GET['zip_code'], 
                )
            ),
            'payable_with' => $_GET['payable_with']
        ));
        

        if($invoice->id){
            $retorno = array(
                'id' => $invoice->id,
                'add' => 'sucess');
        }else{
            if($invoice->errors){
                $retorno = array(
                    'add' => 'error',
                    'msg' => $invoice->errors
                    );
            } else {
                $retorno = array('add' => 'error');
            }

        }
        echo json_encode($retorno);

        
    }
    
    /*
     * @see updateInvoice()
     * Método atualiza fatura
     */
    
    public function updateInvoice($invoice){
        
        $item = Iugu_Invoice::fetch($invoice);
        $item->status = "draft";       
        $item->save();
        
        echo json_encode($item);

    }
    public function cancelInvoice($invoice){
        $item = Iugu_Invoice::fetch($invoice);
        $item->cancel();

        if($item->id){
            $retorno = array(
                'id' => $item->id, 
                'status' => $item->status, 
                'cancel' => 'sucess');
        } else {
            $retorno = array('cancel' => 'error');
        }
        
        echo json_encode($retorno);
        
    }
    public function delInvoice($invoice) {

        if(!empty($invoice)){
            $item = Iugu_Invoice::fetch($invoice);

            $confirm = $_GET['confirm'];

            if($confirm == 1){
                $item->delete();

                if($item->id){
                    $retorno = array(
                        'id' => $item->id,
                        'status' => $item->status,
                        'delete' => 'succes');
                } else {
                    $retorno = $item;
                    
                }
            } else {
                $retorno = array(
                    'delete' => 'error',
                    'msg' => 'Tem certeza que quer remover a fatura? Para confirmar passe o parâmetro confirm = 1'
                    );
            }

            echo json_encode($retorno);    
        } else {
            echo json_encode(array(
                'msg' => 'objeto nulo ou vazio'
            ));
        }
        
        
    }
   
}

class Marketplace extends Iugu_Marketplace {
    
    public function getAccounts(){
        
        $accounts = Iugu_Marketplace::search()->results();
        
        if(count($accounts) != 0){
            foreach ($accounts as $value){              
                $items = array(
                    'id' => $value->id,
                    'name' => $value->name,
                    'verified' => $value->verified
                );                
                echo json_encode($items);
            }     
        }   else {
            echo json_encode(array('error' => 'Sem items'));
        }   
        
        
    }
    
    
    public function addAccounts(){
        $account = Iugu_Marketplace::create(
            array(
                'action' => $_GET['action'],
                'name' => $_GET['name'],
                'comission_percent' => $_GET['comission_percent']

        ));
               
        if($account->errors){
            $retorno = array(
                'errors' => $account->errors
            );
        } else {
            $retorno = array(
                'account_id' => $account->account_id,
                'name' => $account->name,
                'live_api_token' => $account->live_api_token,
                'test_api_token' => $account->test_api_token, 
                'user_token' => $account->user_token,
                'action' => $account->action,
                'comission_percent' => $account->comission_percent
            );    
        }
        
        $db = new Database();
        $PDO = $db->connect();
        $sql = "INSERT INTO accounts ("
                . "account_id, "
                . "account_name, "
                . "account_live_token, "
                . "account_test_token, "
                . "account_user_token,"
                . "account_action, "
                . "account_comission) VALUES ("
                . ":account_id, "
                . ":account_name, "
                . ":account_live_token, "
                . ":account_test_token, "
                . ":account_user_token, "
                . ":account_action, "
                . ":account_comission, "
                . ":account_type)";
        $stmt = $PDO->prepare($sql);
        
        $stmt->bindParam(':account_id', $account_id);
        $stmt->bindParam(':account_name', $account_name);
        $stmt->bindParam(':account_live_token', $account_live_token);
        $stmt->bindParam(':account_test_token', $account_test_token);
        $stmt->bindParam(':account_user_token', $account_user_token);
        $stmt->bindParam(':account_action', $account_action);
        $stmt->bindParam(':account_comission', $account_comission);
        $stmt->bindParam(':account_type', $account_type);
        
        $account_id = $account->account_id;
        $account_name = $account->name;
        $account_live_token = $account->live_api_token;
        $account_test_token = $account->test_api_token;
        $account_user_token = $account->user_token;
        $account_action = $account->action;
        $account_comission = $account->comission_percent;
        $account_comission = 'sub';
        
        echo json_encode($retorno);
        $stmt->execute();
    }
    
}

class Account extends Iugu_Account {
    
    public function viewAccount($account){
        
        $accounts = Iugu_Account::fetch($account);
        
        if($accounts->id){
            $retorno = array(
                'id' => $accounts->id,
                'name' => $accounts->name,
                'created_at' => $accounts->created_at,
                'updated_at' => $accounts->updated_at,
                'can_receive?' => $accounts['can_receive?'],
                'is_verified?' => $accounts['is_verified?'],
                'last_verification_request_status' => $accounts->last_verification_request_status,
                'last_verification_request_data' => array($accounts->last_verification_request_data),
                'last_verification_request_feedback' => $accounts->last_verification_request_feedback,
                'change_plan_type' => $accounts->change_plan_type,
                'subscriptions_trial_period' => $accounts->subscriptions_trial_period,
                'subscriptions_billing_days' => $accounts->subscriptions_billing_days,
                'disable_emails' => $accounts->disable_emails,
                'last_withdraw' => $accounts->last_withdraw,
                'reply_to' => $accounts->reply_to,
                'webapp_on_test_mode' => $accounts->webapp_on_test_mode,
                'marketplace' => $accounts->marketplace,
                'default_return_url' => $accounts->default_return_url,
                'credit_card_verified' => $accounts->credit_card_verified,
                'fines' => $accounts->fines,
                'late_payment_fine' => $accounts->late_payment_fine,
                'per_day_interest' => $accounts->per_day_interest,
                'old_advancement' => $accounts->old_advancement,
                'early_payment_discount' => $accounts->early_payment_discount,
                'early_payment_discount_days' => $accounts->early_payment_discount_days,
                'early_payment_discount_percent' => $accounts->auto_withdraw,
                'auto_withdraw' => $accounts->auto_withdraw,
                'payment_email_notification' => $accounts->payment_email_notification,
                'payment_email_notification_receiver' => $accounts->payment_email_notification_receiver,
                'auto_advance' => $accounts->auto_advance,
                'auto_advance_type' => $accounts->auto_advance_type,
                'auto_advance_option' => $accounts->auto_advance_option,
                'balance' => $accounts->balance,
                'balance_in_protest' => $accounts->balance_in_protest,
                'balance_available_for_withdraw' => $accounts->balance_available_for_withdraw,
                'protected_balance' => $accounts->protected_balance,
                'payable_balance' => $accounts->payable_balance,
                'receivable_balance' => $accounts->receivable_balance,
                'commission_balance' => $accounts->commission_balance,
                'volume_last_month' => $accounts->volume_last_month,
                'volume_this_month' => $accounts->volume_this_month,
                'total_subscriptions' => $accounts->total_subscriptions,
                'total_active_subscriptions' => $accounts->total_active_subscriptions,
                'taxes_paid_last_month' => $accounts->taxes_paid_last_month,
                'taxes_paid_this_month' => $accounts->taxes_paid_this_month,
                'has_bank_address?' => $accounts['has_bank_address?'],
                'permissions' => $accounts->permissions,
                'custom_logo_url' => $accounts->custom_logo_url,
                'custom_logo_small_url' => $accounts->custom_logo_small_url,
                'early_payment_discounts' => $accounts->early_payment_discounts,
                'informations' => $accounts->informations,
                'configuration' => $accounts->configuration,
                  'bank_slip' => $accounts->bank_slip,
                  'credit_card' => $accounts->credit_card,
                  'early_payment_discount' => $accounts->early_payment_discount,
                  'early_payment_discount_days' => $accounts->early_payment_discount_days,
                  'early_payment_discount_percent' => $accounts->early_payment_discount_percent
            );
        } else {
            $retorno = array('cancel' => 'error');
        }
        
        echo json_encode($retorno);
        
    }
    
    public function verAccount($account){
        $validate = Iugu_Account::create(array(
        "id" => $account,
        "action" => "request_verification",
        "automatic_validation" => false,
        "files" => false,
        "data" => array(
            "price_range" => "Mais que R$ 500,00",
            "physical_products" => false,
            "business_type" => "TIPO DO CLIENTE",
            "person_type" => "Pessoa Física",
            "automatic_transfer" => false,
            "cnpj" => false,
            "cpf" => '04687436420',
            "name" => "Nome Teste",
            "address" => "endereço",
            "cep" => "59069400",
            "city" => "Natal",
            "state" => "RN",
            "telephone" => "27 33333333",
            "bank" => 'Banco do Brasil',
            "bank_ag" => '9999-9',
            "account_type" => 'Corrente',
            "bank_cc" => '999999-9'
        )
        ));
        
        if($validate->errors){
            $retorno = array($validate->errors);
        } else {
            $retorno = array(
                    'id' => $validate->id,
                    'data' => array($validate->data),
                    'account_id' => $validate->account_id,
                    'created_at' => $validate->created_at
            );
        }
        echo json_encode($retorno);
    }
}
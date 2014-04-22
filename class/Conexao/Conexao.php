<?php

class Conexao extends PDO {
    
    private $dsn;
    private $user = 'cont9rg2_sdcbd';
    private $password = 'workambbd2013';
    private $host = 'localhost';
    private $dbName = 'cont9rg2_siscatalogo';
    public static $instancia = null;
    
    function __construct() {
        //$host = ($_SERVER['SERVER_ADDR'] == '127.0.0.1') ? 'localhost' : '10.2.8.27';
        $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
        parent::__construct($this->dsn, $this->user, $this->password);
    }

    public static function getInstance() {
        // Se a instancia n�o existe cria uma inst�ncia
        if (!isset(self::$instancia)) {
            try {
                self::$instancia = new Conexao();
                self::$instancia->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $ex) {
                echo 'Falha ao conectar com o banco. Erro: ' . $ex->getMessage();
                exit();
            }
        }
        // Se j� existe instancia na mem�ria retorna ela
        return self::$instancia;
    }

    //aqui fechamos a conex�o
    function __destruct() {
        $this->handle = NULL;
    }

}

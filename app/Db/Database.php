<?php

namespace App\Db;
use \PDO;
use \PDOException;

class Database{

    private const HOST = 'localhost';
    private const NAME = 'wdev_vagas';
    private const USER = 'root';
    private const PASS = '';

    private $table;
    private $connection;

    public function __construct($table = null){
        $this->table = $table;
        $this->setConnection();
    }
    private function setConnection(){
        try{
            $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die('ERROR: '.$e->getMessage());
        }
    }

    /*Método responsavel por executar queries dentro do banco de dados*/
    public function execute($query,$params = []){
        try{
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        }catch(PDOException $e){
            die('ERROR: '.$e->getMessage());
        } 
    }

    /* Método responsavel por inserir dados no banco */
    public function insert($values){

        //DADOS DA QUERY
        $fields = array_keys($values);
        $binds = array_pad([],count($fields), '?');

        //MONTA A QUERY
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

        //Executa o insert
        $this->execute($query,array_values($values));
        
        //Retorna o id inserido
        return $this->connection->lastInsertId();
    }

    /*Método responsavel por executar uma consulta no banco*/
    public function select($where = null, $order = null, $limit = null, $fields = '*'){

        //DADOS DA QUERY 
        $where = strlen($where) ? 'WHERE'.$where : '';
        $order = strlen($order) ? 'ORDER BY'.$order : '';
        $where = strlen($where) ? 'LIMIT'.$where : '';

        //MONTA A QUERY
        $query = 'SELECT '.$fields.' FROM '.$this->table.' '. $where.' '.$order.' '.$limit;

        //EXECUTA A QUERY
        return $this->execute($query);
    }


}
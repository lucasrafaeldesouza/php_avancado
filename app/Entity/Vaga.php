<?php

namespace App\Entity;

use \PDO;
use \App\Db\Database;

class Vaga
{
    public $id;
    public $titulo;
    public $descricao;
    public $ativo;
    public $data;

    /*Método responsavel por cadastrar uma nova vaga no banco */
    public function cadastrar()
    {

        date_default_timezone_set('America/Sao_Paulo');
        //echo date_default_timezone_get();
        $this->data = date('Y-m-d H:i:s');
        $obDatabase = new Database('vagas');
        $this->id = $obDatabase->insert([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'data' => $this->data
        ]);

        return true;
    }

    /* Método responsavel por obter as vagas do banco de dados */
    public static function getVagas($where = null, $order = null, $limit = null)
    {
        return (new Database('vagas'))->select($where, $order, $limit)
            ->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transacoes extends Migration
{
    public function up()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();

        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'id_pedido' => [
                'type' => 'VARCHAR',
                'CONSTRAINT' => 255
            ],

            'id_adm' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            'id_user' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            'id_cliente' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            'id_gateway' => [
                'type' => 'int',
                'unsigned' => true
            ],

            'id_transacao' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],

            "pago" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false
            ],
            
            /**atualização */
            "tipo_pagamento" => [
                "type" => "VARCHAR",
                "constraint" => 20
            ],
            "descricao" => [
                "type" => "VARCHAR",
                "constraint" => 20
            ],

            "descricao_longa" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "null" => true
            ],
            
            "data_pagamento" => [
                "type" => "datetime",
                "null" => true
            ],

            "status_text" => [
                "type" => "VARCHAR",
                "constraint" => 60
            ],
            
            /** final */
            "valor" => [
                'type' => "DECIMAL",
                'constraint' => '10,2'
            ],

            "log" => [
                'TYPE' => "TEXT"
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        /*$this->forge->addForeignKey('id_cliente', 'usuarios', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('id_adm', 'administracao', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('id_user', 'usuarios', 'id', 'NO ACTION', 'NO ACTION');*/
        $this->forge->createTable('transacoes', true);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('transacoes', true);
        $db->enableForeignKeyChecks();
    }
}

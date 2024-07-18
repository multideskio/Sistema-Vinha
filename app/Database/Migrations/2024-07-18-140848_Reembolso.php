<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Reembolso extends Migration
{
    public function up()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_admin' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_transacao' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'descricao' => [
                'type' => 'TEXT'
            ],

            'valor' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
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
        //$this->forge->addForeignKey('id_transacao', 'transacoes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reembolsos', true);
        

        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('reembolsos', true);
        $db->enableForeignKeyChecks();
    }
}

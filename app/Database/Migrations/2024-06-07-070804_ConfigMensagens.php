<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ConfigMensagens extends Migration
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
                'auto_increment' => true,
                'constraint' => 1
            ],

            'id_adm' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            'id_user' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            "tipo" => [
                'type' => 'VARCHAR',
                'constraint' => 30
            ],

            "mensagem" => [
                'type' => 'TEXT'
            ],

            "status" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false
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
        $this->forge->createTable('config_mensagens', true);
        $db->enableForeignKeyChecks();

    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('config_mensagens', true);
        $db->enableForeignKeyChecks();
    }
}

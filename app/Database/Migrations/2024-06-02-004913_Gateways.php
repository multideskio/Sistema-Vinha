<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Gateways extends Migration
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
                'constraint' => 30,
                'COMMENT'=> 'CIELO OU BRADESCO'
            ],
            
            "status" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false,
                'COMMENT' => '1 = PRODUÇÃO'
            ],
            "active_pix" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false
            ],
            "active_credito" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false
            ],
            "active_debito" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false
            ],
            "active_boletos" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false
            ],
            'merchantid_pro' => [
                'type' => "VARCHAR",
                'constraint' => 255
            ],
            'merchantkey_pro' => [
                'type' => "VARCHAR",
                'constraint' => 255
            ],

            'merchantid_dev' => [
                'type' => "VARCHAR",
                'constraint' => 255
            ],

            'merchantkey_dev' => [
                'type' => "VARCHAR",
                'constraint' => 255
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
        $this->forge->createTable('gateways', true);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('gateways', true);
        $db->enableForeignKeyChecks();
    }
}

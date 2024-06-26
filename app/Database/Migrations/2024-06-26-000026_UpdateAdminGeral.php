<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAdminGeral extends Migration
{
    public function up()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();

        $fields = [
            "ativar_wa" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => true,
                'AFTER' => 'key_api'
            ],
            "ativar_smtp" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => true,
                'AFTER' => 'smtp_port'
            ],
            "facebook" => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => 80,
                'AFTER' => 'celular'
            ],
            "site" => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => 80,
                'AFTER' => 'celular'
            ],
            "instagram" => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => 80,
                'AFTER' => 'celular'
            ]
        ];
        $this->forge->addColumn('administracao', $fields);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropColumn('administracao', ['ativar_wa', 'ativar_smtp', 'facebook', 'site', 'instagram']);
        $db->enableForeignKeyChecks();
    }
}
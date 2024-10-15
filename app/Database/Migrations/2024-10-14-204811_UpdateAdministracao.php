<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAdministracao extends Migration
{
    public function up()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->addColumn('administracao', [
            "prazo_boleto" => [
                'type'       => 'int',
                'constraint' => 2,
                'AFTER'      => 's3_cdn',
            ],
            "instrucoes_boleto" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'prazo_boleto',
            ],
        ]);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropColumn('administracao', ['prazo_boleto', 'instrucoes_boleto']);
        $db->enableForeignKeyChecks();
    }
}
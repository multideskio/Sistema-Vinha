<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersGeralEndereco extends Migration
{
    public function up()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->addColumn('pastores', [
            "numero" => [
                'type'       => 'int',
                'constraint' => 4,
                'AFTER'      => 'bairro',
            ],
            "rua" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
            "pais" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
        ]);

        $this->forge->addColumn('igrejas', [
            "numero" => [
                'type'       => 'int',
                'constraint' => 4,
                'AFTER'      => 'bairro',
            ],
            "rua" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
            "pais" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
        ]);

        $this->forge->addColumn('supervisores', [
            "numero" => [
                'type'       => 'int',
                'constraint' => 4,
                'AFTER'      => 'bairro',
            ],
            "rua" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
            "pais" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
        ]);

        $this->forge->addColumn('gerentes', [
            "numero" => [
                'type'       => 'int',
                'constraint' => 4,
                'AFTER'      => 'bairro',
            ],
            "rua" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
            "pais" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
        ]);

        $this->forge->addColumn('administradores', [
            "numero" => [
                'type'       => 'int',
                'constraint' => 4,
                'AFTER'      => 'bairro',
            ],
            "rua" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
            "pais" => [
                'type'       => 'varchar',
                'constraint' => 255,
                'AFTER'      => 'bairro',
            ],
        ]);

        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropColumn('administradores', ['numero', 'rua', 'pais']);
        $this->forge->dropColumn('gerentes', ['numero', 'rua', 'pais']);
        $this->forge->dropColumn('supervisores', ['numero', 'rua', 'pais']);
        $this->forge->dropColumn('igrejas', ['numero', 'rua', 'pais']);
        $this->forge->dropColumn('pastores', ['numero', 'rua', 'pais']);
        $db->enableForeignKeyChecks();
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRelatoriosGeradosTable extends Migration
{
    public function up()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'id_adm' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            'id_user' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            'nome_arquivo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'url_download' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'parametros_busca' => [
                'type' => 'TEXT', // Armazena os parâmetros em formato JSON
                'null' => true,
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
        $this->forge->addForeignKey('id_user', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('relatorios_gerados');
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('relatorios_gerados');
        $db->enableForeignKeyChecks();
    }
}

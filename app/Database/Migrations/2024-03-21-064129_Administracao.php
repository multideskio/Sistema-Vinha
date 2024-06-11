<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Administracao extends Migration
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
            ],
            'cnpj' => [
                'type' => 'VARCHAR',
                'constraint' => '21',
                'null' => true
            ],
            'empresa' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true
            ],
            'logo' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true
            ],
            'cep' => [
                'type' => 'VARCHAR',
                'constraint' => '9',
                'null' => true
            ],
            'uf' => [
                'type' => 'VARCHAR',
                'constraint' => '2',
                'null' => true
            ],
            'cidade' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true
            ],
            'bairro' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true
            ],
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => true
            ],
            'telefone' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => true
            ],
            'celular' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => true
            ],
            'email_remetente' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true
            ],
            'nome_remetente' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true
            ],
            'url_api' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true,
                'COMMENT' => 'Evolution API'
            ],
            'instance_api' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true,
                'COMMENT' => 'Evolution API'
            ],
            'key_api' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true,
                'COMMENT' => 'Evolution API'
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
        $this->forge->createTable('administracao', true);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $this->forge->dropTable('administracao', true);
    }
}

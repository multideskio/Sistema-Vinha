<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Igrejas extends Migration
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
            'id_adm' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'id_user' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'id_supervisor' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'nome_tesoureiro' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],
            'sobrenome_tesoureiro' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],
            'cpf_tesoureiro' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],
            'fundacao' => [
                'type' => 'date'
            ],
            'razao_social' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],
            'fantasia' => [
                'type' => 'VARCHAR',
                'constraint' => 90
            ],
            'cnpj' => [
                'type' => 'CHAR',
                'constraint' => 14
            ],
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 160
            ],
            'uf' => [
                'type' => 'CHAR',
                'constraint' => 2
            ],
            'cidade' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],
            'cep' => [
                'type' => 'VARCHAR',
                'constraint' => 9
            ],
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => 120
            ],
            'bairro' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],
            'data_dizimo' => [
                'type' => 'INT',
                'constraint' => 2
            ],
            'telefone' => [
                'type' => 'VARCHAR',
                'constraint' => 15
            ],
            'celular' => [
                'type' => 'VARCHAR',
                'constraint' => 15
            ],
            'facebook' => [
                'type' => 'VARCHAR',
                'constraint' => 160
            ],
            'instagram' => [
                'type' => 'VARCHAR',
                'constraint' => 160
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
        $this->forge->createTable('igrejas', true);
        $db->enableForeignKeyChecks();
        
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('igrejas', true);
        $db->enableForeignKeyChecks();
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Perfis extends Migration
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

            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],

            'sobrenome' => [
                'type' => 'VARCHAR',
                'constraint' => 60
            ],
            
            'cpf' => [
                'type' => 'CHAR',
                'constraint' => 20
            ],

            'nascimento' => [
                'type' => 'DATE',

            ],

            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255
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
                'constraint' => 255
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
                'constraint' => 30
            ],
            
            'celular' => [
                'type' => 'VARCHAR',
                'constraint' => 30
            ],
            
            'facebook' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            
            'instagram' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'website' => [
                'type' => 'VARCHAR',
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
        $this->forge->createTable('pastores', true);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('pastores', true);
        $db->enableForeignKeyChecks();
    }
}

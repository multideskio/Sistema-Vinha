<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Administrador extends Migration
{
    public function up()
    {
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
        $this->forge->createTable('administradores', true);
        $db->enableForeignKeyChecks();

        $seeder = \Config\Database::seeder();
        $seeder->call('Config');
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('administradores', true);
        $db->enableForeignKeyChecks();
    }
}

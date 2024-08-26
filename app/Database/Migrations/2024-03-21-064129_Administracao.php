<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Administracao extends Migration
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
            'cnpj' => [
                'type' => 'VARCHAR',
                'constraint' => 30, // CNPJ geralmente tem 14 caracteres
                'null' => true
            ],
            'empresa' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'cep' => [
                'type' => 'VARCHAR',
                'constraint' => 9,
                'null' => true
            ],
            'uf' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => true
            ],
            'cidade' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'bairro' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
                'null' => true
            ],
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'telefone' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true
            ],
            'celular' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true
            ],
            'email_remetente' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'nome_remetente' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'url_api' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Evolution API'
            ],
            'instance_api' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Evolution API'
            ],
            'key_api' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Evolution API'
            ],
            'smtp_host' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'smtp_user' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'smtp_pass' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true
            ],
            'smtp_port' => [
                'type' => 'SMALLINT',
                'constraint' => 5,
                'null' => true
            ],
            'smtp_crypt' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
                'default' => 'tls'
            ],
            'google_client_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'google_client_secret' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
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
        $this->forge->dropTable('administracao', true);
    }
}

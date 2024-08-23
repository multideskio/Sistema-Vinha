<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usuarios extends Migration
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
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'COMMENT' => 'Define o tipo de usuário e em qual tabela busca os dados para a sessão'
            ],
            "id_perfil" => [
                'type' => 'int',
                'unsigned' => true,
                'COMMENT' => 'ID do perfil do usuário na tabela definida, GERENTES, SUPERVISORES, PASTOR, IGREJAS'
            ],
            "email" => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'unique'     => true,
            ],
            "password" => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true
            ],
            "token" => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true
            ],
            "nivel" => [
                'type' => 'int',
                'constraint' => 1,
                'COMMENT' => '1 - Superadmin, 2 - Gerente, 3 - Supervisor, 4 - Pastor/Igreja'
            ],
            "confirmado" => [
                'type' => 'BOOLEAN',
                'null' => false,
                'DEFAULT' => false
            ],
            "id_adm" => [
                'type' => 'int',
                'unsigned' => true,
                'null' => true,
                'COMMENT' => 'Usuário que cadastrou',
            ],
            'whatsapp' => [
                'type' => 'BOOLEAN',
                'COMMENT' => 'Permissão para enviar mensagens via whatsapp',
                'DEFAULT' => true
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
        $this->forge->createTable('usuarios', true);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $this->forge->dropTable('usuarios', true);

    }
}

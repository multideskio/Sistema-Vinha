<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Ajuda extends Migration
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
                'auto_increment' => true
            ],
            'id_admin' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'CONSTRAINT' => 60
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'CONSTRAINT' => 255
            ],
            'conteudo' => [
                'type' => 'TEXT'
            ],
            'tags' => [
                'type' => 'VARCHAR',
                'CONSTRAINT' => 255
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
        $this->forge->createTable('ajuda', true);
        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        //
        $db = db_connect();
        $db->disableForeignKeyChecks();
        $this->forge->dropTable('ajuda', true);
        $db->enableForeignKeyChecks();
    }
}

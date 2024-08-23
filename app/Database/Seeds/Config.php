<?php

namespace App\Database\Seeds;

use App\Models\AdministradoresModel;
use App\Models\AdminModel;
use App\Models\PerfisModel;
use App\Models\UsuariosModel;
use CodeIgniter\Database\Seeder;

class Config extends Seeder
{
    public function run()
    {
        //
        echo "Inicio de seeds... \n";

        $adminModel = new AdminModel();
        $admModel   = new AdministradoresModel();
        $userModel  = new UsuariosModel();

        /* This code snippet is inserting data into the `admin` table using the `AdminModel` model. The
        `insert` method of the `AdminModel` is being called with an associative array containing the
        data to be inserted. The keys in the array correspond to the column names in the `admin`
        table, and the values are the data to be inserted into those columns. */
        if ($userModel->where('email', 'igrsysten@gmail.com')->countAllResults() == 0) {
            $idAdmin = $adminModel->insert([
                'empresa'         => 'Vinha Ministérios',
                'email_remetente' => 'multidesk.io@gmail.com',
                'nome_remetente'  => 'Multidesk.io'
            ]);

            echo "ID ADMIN: $idAdmin \n";

            /* This code snippet is inserting data into the `administradores` table using the
        `AdministradoresModel` model. The `insert` method of the `AdministradoresModel` is being
        called with an associative array containing the data to be inserted. */
            $idAdm = $admModel->insert([
                'id_adm'   => $idAdmin,
                'nome'     => 'Paulo',
                'cpf'      => '037.628.391-23'
            ]);

            echo "ID SUPERADMIN: $idAdm \n";

            /* This code snippet is inserting data into the `usuarios` table using the `UsuariosModel`
        model. The `insert` method of the `UsuariosModel` is being called with an associative array
        containing the data to be inserted. Here's a breakdown of the data being inserted: */
            $idUser = $userModel->insert([
                'tipo' => 'superadmin',
                'id_perfil' => $idAdm,
                'email'    => 'igrsysten@gmail.com',
                'password' => "123456",
                'token'    => bin2hex(random_bytes(16)),
                'nivel'    => 1,
                'id_adm'   => $idAdmin,
                'confirmado' => true
            ]);

            echo "ID USER: $idUser \n\n";
            echo "Dados inseridos... \n";

        } else {
            echo "Usuário já existe";
        }
    }
}

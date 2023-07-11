<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'email'=>[
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone'=>[
                'type' => 'VARCHAR',
                'constraint' => 20,
                'rules' => 'regex_match[/^[0-9]+$/]',
            ],
            'screen_name'=>[
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'created_at'=>[
                'type' => 'DATETIME',
            ],
            'updated_at'=>[
                'type' => 'DATETIME',
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('phone');
        $this->forge->createTable('user');
    }
    
    public function down()
    {
        $this->forge->dropTable('user');
    }
}

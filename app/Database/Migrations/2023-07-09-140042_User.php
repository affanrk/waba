<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                // 'unsigned' => TRUE,
                // 'auto_increment' => TRUE,
            ],
            'email'=>[
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'phone'=>[
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
                // 'rules' => 'regex_match[/^[0-9]+$/]',
            ],
            'screen_name'=>[
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'profile'=>[
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'created_at'=>[
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'updated_at'=>[
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'deleted_at'=>[
                'type' => 'DATETIME',
                'null' => TRUE,
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->createTable('user');
    }
    
    public function down()
    {
        $this->forge->dropTable('user');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Chat extends Migration
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
            'id_room' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'message'=>[
                'type'=>'TEXT',
                'null'=>TRUE,
            ],
            'media'=>[
                'type'=>'TEXT',
                'null'=>TRUE,
            ],
            'is_active'=>[
                'type'=>'INT',
                'constraint'=>1,
                'default'=>1,
            ],
            'created_at'=>[
                'type'=>'DATETIME',
            ]
        ]);
        
        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('id_room', 'room', 'id');
        $this->forge->addForeignKey('id_user', 'user', 'id');
        $this->forge->createTable('chat');
    }
    
    public function down()
    {
        $this->forge->dropTable('chat');
    }
}

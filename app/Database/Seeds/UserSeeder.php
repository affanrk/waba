<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'screen_name' =>   'reja',
                'avatar'    => '',
            ],
            [
                'screen_name' =>   'affan',
                'avatar'    => '',
            ],
            [
                'screen_name' =>   'wildan',
                'avatar'    => '',
            ],
            [
                'screen_name' =>   'rakha',
                'avatar'    => '',
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}

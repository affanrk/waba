<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'reja',
                'avatar'    => '',
            ],
            [
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'wildan',
                'avatar'    => '',
            ],
            [
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'affan',
                'avatar'    => '',
            ],
            [
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'rakha',
                'avatar'    => '',
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}

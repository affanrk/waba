<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'    => '1',
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'reja',
                'profile'    => '',
            ],
            [
                'id'    => '2',
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'wildan',
                'profile'    => '',
            ],
            [
                'id'    => '3',
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'affan',
                'profile'    => '',
            ],
            [
                'id'    => '4',
                'email' =>   '',
                'phone' =>   '',
                'screen_name' =>   'rakha',
                'profile'    => '',
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}

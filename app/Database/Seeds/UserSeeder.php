<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currentDateTime = date("Y-m-d H:i:s");
        $data = [
            [
                'email'         => 'affan.kurniadi@gmail.com',
                'phone'         => '085809094091',
                'screen_name'   => 'affan rifqy',
                'created_at'    => $currentDateTime,
                'updated_at'    => $currentDateTime,
            ],
            // [
            //     'email' =>   'affan.kurniadi@gmail.com',
            //     'phone' =>   '085809094091',
            //     'screen_name' =>   'affan rifqy',
            //     'created_at' => $currentDateTime,
            //     'updated_at' => $currentDateTime,
            // ]
        ];
        $this->db->table('user')->insertBatch($data);
    }
}

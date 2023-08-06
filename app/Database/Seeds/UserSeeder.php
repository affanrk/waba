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
                'email'         => 'affan7@gmail.com',
                'phone'         => '085809094087',
                'screen_name'   => 'affan 7',
                'created_at'    => $currentDateTime,
                'updated_at'    => $currentDateTime,
            ],
            [
                'email'         => 'affan8@gmail.com',
                'phone'         => '085809094088',
                'screen_name'   => 'affan 8',
                'created_at'    => $currentDateTime,
                'updated_at'    => $currentDateTime,
            ],
            [
                'email'         => 'affan9@gmail.com',
                'phone'         => '085809094089',
                'screen_name'   => 'affan 9',
                'created_at'    => $currentDateTime,
                'updated_at'    => $currentDateTime,
            ],
            [
                'email'         => 'affan10@gmail.com',
                'phone'         => '085809094090',
                'screen_name'   => 'affan 10',
                'created_at'    => $currentDateTime,
                'updated_at'    => $currentDateTime,
            ],
            [
                'email'         => 'affan11@gmail.com',
                'phone'         => '085809094097',
                'screen_name'   => 'affan 11',
                'created_at'    => $currentDateTime,
                'updated_at'    => $currentDateTime,
            ],
        ];
        $this->db->table('user')->insertBatch($data);
    }
}

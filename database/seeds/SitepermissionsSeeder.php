<?php

use Illuminate\Database\Seeder;
use App\Models\SitePermission;

class SitepermissionsSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $permissions = [
        [
            'role_id'                       =>  1,
            'manager_id'                     =>  1,
            'is_read'                     =>  false,
            'is_add'                     =>  true,
            'is_edit'                     =>  true,
            'is_delete'                     =>  false,
            'status'                     =>  false,
        ],
        [
            'role_id'                       =>  1,
            'manager_id'                     =>  2,
            'is_read'                     =>  true,
            'is_add'                     =>  true,
            'is_edit'                     =>  true,
            'is_delete'                     =>  false,
            'status'                     =>  false,
        ],
        [
            'role_id'                       =>  1,
            'manager_id'                     =>  3,
            'is_read'                     =>  true,
            'is_add'                     =>  true,
            'is_edit'                     =>  true,
            'is_delete'                     =>  false,
            'status'                     =>  false,
        ],
        [
            'role_id'                       =>  1,
            'manager_id'                     =>  4,
            'is_read'                     =>  false,
            'is_add'                     =>  false,
            'is_edit'                     =>  true,
            'is_delete'                     =>  false,
            'status'                     =>  true,
        ],
        [
            'role_id'                       =>  1,
            'manager_id'                     =>  5,
            'is_read'                     =>  false,
            'is_add'                     =>  false,
            'is_edit'                     =>  true,
            'is_delete'                     =>  false,
            'status'                     =>  true,
        ],
        [
            'role_id'                       =>  1,
            'manager_id'                     =>  6,
            'is_read'                     =>  false,
            'is_add'                     =>  false,
            'is_edit'                     =>  true,
            'is_delete'                     =>  false,
            'status'                     =>  true,
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->permissions as $index => $menu)
        {
            $result = SitePermission::create($menu);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted '.count($this->permissions). ' records');
    }
}

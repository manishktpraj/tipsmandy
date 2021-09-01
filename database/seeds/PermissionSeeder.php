<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $permissions = [
        [
            'name'                       =>  'View',
            'slug'                     =>  'view',
        ],
        [
            'name'                       =>  'Add',
            'slug'                     =>  'add',
        ],
        [
            'name'                       =>  'Edit',
            'slug'                     =>  'edit',
        ],
        [
            'name'                       =>  'Delete',
            'slug'                     =>  'delete',
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
            $result = Permission::create($menu);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted '.count($this->permissions). ' records');
    }
}

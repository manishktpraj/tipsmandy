<?php

use Illuminate\Database\Seeder;
use App\Models\Manager;

class ManagerSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $managers = [
        [
            'name'                       =>  'Staff Members',
            'slug'                     =>  'staff-members',
        ],
        [
            'name'                       =>  'Manage Members',
            'slug'                     =>  'manage-members',
        ],
        [
            'name'                       =>  'Plans',
            'slug'                     =>  'plans',
        ],
        [
            'name'                       =>  'Manage Tips',
            'slug'                     =>  'manage-tips',
        ],
        [
            'name'                       =>  'Roles',
            'slug'                     =>  'roles',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->managers as $index => $menu)
        {
            $result = Manager::create($menu);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted '.count($this->managers). ' records');
    }
}

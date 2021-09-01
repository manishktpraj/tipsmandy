<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $roles = [
        [
            'name'                       =>  'Sub Admin',
        ],
        [
            'name'                       =>  'Tele-Callers',
        ],
        [
            'name'                       =>  'Experts',
        ],
        [
            'name'                       =>  'Content Creators',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $index => $setting)
        {
            $result = Role::create($setting);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }

        $this->command->info('Inserted '.count($this->roles). ' records');
    }
}

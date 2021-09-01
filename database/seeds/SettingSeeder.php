<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{

	/**
     * @var array
     */
    protected $settings = [
        [
            'key'                       =>  'whats_app_number',
            'value'                     =>  '9782707662',
        ],
        [
            'key'                       =>  'email',
            'value'                     =>  'stock@support.com',
        ],
        [
            'key'                       =>  'youtube_video_link',
            'value'                     =>  'https://www.youtube.com',
        ],
        [
            'key'                       =>  'youtube_thumbnail',
            'value'                     =>  'https://www.youtube.com',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting)
        {
            $result = Setting::create($setting);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted '.count($this->settings). ' records');
    }
}

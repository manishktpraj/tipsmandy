<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Gloabal Constants
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'pagination_records' => 20,

    'paginate' => 20,

    'segments' => [
                    'Delivery' => 'Delivery',
                    'Intraday' => 'Intraday',
                    'Future' => 'Future',
                    'Option' => 'Option',
                    'Currency' => 'Currency',
                    'Commodity' => 'Commodity',
                    'Boolean' => 'Boolean',
                    'NCD' => 'NCD',
                    'IPOs' => 'IPOs',
                    'FDs' => 'FDs',
                    'MF' => 'MF',
                ],
	
	'marketExperience'  => [1 => 'Beginner', 2 => 'Intermediate level', 3 => 'Experts'],

    'ratings'  => ['1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5'],

    'mfFundTypes'  => ['Large Cap', 'Small Cap', 'Medium Cap', 'Money Market Funds', 'Fixed Income', 'Balanced Funds', 'Hybrid / Monthly Income Plans', 'Gilt Funds'],
    'mfPurpose'  => ['Child Education', 'Retirement', 'Fixed Income'],
    
	'planMonths'  => [1 => 'Monthly', 3 => 'Quarters', 6 => 'Half yearly', 12 => 'Annually'],

];

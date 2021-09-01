<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipsTarget extends Model
{
    /**
     * @var string
     */
    protected $table = 'tips_targets';

    /**
     * @var array
     */
    protected $fillable = ['tip_id', 'name', 'price', 'is_achieved', 'created_by', 'updated_by'];


    public function createTarget($target_names, $target_prices, $tipId)
    {
        foreach ($target_names as $target_name_key => $target_name_value) {

            if(!empty($target_prices[$target_name_key]) && !empty($target_name_value)) {

                self::create([
                    'tip_id' => $tipId,
                    'name' => $target_name_value,
                    'price' => $target_prices[$target_name_key],
                    'created_by' => self::adminId(),
                    'updated_by' => self::adminId(),
                ]);
            }
        }
    }

    private function adminId()
    {
        return Auth::guard('admin')->user()->id;
    }
}

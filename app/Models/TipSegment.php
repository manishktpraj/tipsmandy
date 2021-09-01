<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipSegment extends Model
{
    /**
     * @var string
     */
    protected $table = 'tip_segments';

    /**
     * @var array
     */
    protected $fillable = ['tip_id', 'name'];



    public function createSegment($segments, $tipId)
    {
        if(is_array($segments)) {

            foreach ($segments as $segment_key => $segment_value) {

                if(!empty($segment_value)) {

                    self::create([
                        'tip_id' => $tipId,
                        'name' => $segment_value
                    ]);
                }
            }
        }
    }
}

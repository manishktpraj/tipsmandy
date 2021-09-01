<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnVideo extends Model
{
    /**
     * @var string
     */
    protected $table = 'learn_videos';

    /**
     * @var array
     */
    protected $fillable = ['title', 'sub_title', 'video_url', 'content', 'icon'];


    /**
     * Get the video title.
     */
    public function videoTitle()
    {
        return $this->belongsTo(VideoCategory::class, 'title')->withDefault([
                    'title' => 'uncategorized',
                ]);
    }
}

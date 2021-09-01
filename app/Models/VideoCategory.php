<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    /**
     * @var string
     */
    protected $table = 'video_categories';

    /**
     * @var array
     */
    protected $fillable = ['title', 'slug'];
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function learnVideos()
    {
        return $this->hasMany(LearnVideo::class, 'title');
    }
}

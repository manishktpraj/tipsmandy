<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEdgeVideo extends Model
{
    /**
     * @var string
     */
    protected $table = 'stock_edge_videos';

    /**
     * @var array
     */
    protected $fillable = ['title', 'youtube_url', 'youtube_thumbnail'];
}

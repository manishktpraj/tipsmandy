<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketStockData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'market_stock_data';

    /**
     * @var array
     */
    protected $fillable = ['security_code', 'symbol', 'name', 'exchange'];
}

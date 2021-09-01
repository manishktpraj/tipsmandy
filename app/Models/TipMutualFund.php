<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipMutualFund extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tip_mutual_funds';

    /**
     * @var array
     */
    protected $fillable = [
            'tip_id',
            'caps_type',
            'purpose',
            'scheme_code',
            'isin_div_payout_isin_growth',
            'isin_div_reinvestment',
            'scheme_name',
            'net_asset_value',
            'mutual_date',
            'mutual_nav_date',
            'scheme_type',
            'scheme_category',
            'mutual_fund_family',
            'mf_api'
        ];
}

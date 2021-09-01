<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Tip extends Model
{
    /**
     * @var string
     */
    protected $table = 'tips';

    /**
     * @var array
     */
    //protected $fillable = ['source_id', 'name', 'price', 'buy_range', 'stop_loss', 'segment', 'tipsplans', 'symbol', 'symbols', 'created_by', 'updated_by'];
    protected $guarded = [];

    /**
     *
     * Get plan price detail.
     */
    public function getTipSegmentDetail($tipId, $name)
    {
        return TipSegment::where(['tip_id' => $tipId, 'name' => $name])->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tipsPlans()
    {
        return $this->hasMany(TipPlan::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tipsTipstargets()
    {
        return $this->hasMany(TipsTarget::class);
    }

    /**
     * Get the source detail.
     */
    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ncdInvestments()
    {
        return $this->hasMany(TipNcdInvesment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tipmutualfunds()
    {
        return $this->hasOne(TipMutualFund::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tip_mutual_funds()
    {
        return $this->hasOne(TipMutualFund::class);
    }

    /**
     * Get the user detail.
     */
    public function adminDetail()
    {
        return $this->belongsTo('App\Admin', 'created_by')->withDefault();
    }

    private function adminId()
    {
        return Auth::guard('admin')->user()->id;
    }

    public function createTarget($target_names, $target_prices, $tipId)
    {
        foreach ($target_names as $target_name_key => $target_name_value) {

            if(!empty($target_prices[$target_name_key]) && !empty($target_name_value)) {

                TipsTarget::create([
                    'tip_id' => $tipId,
                    'name' => $target_name_value,
                    'price' => $target_prices[$target_name_key],
                    'created_by' => self::adminId(),
                    'updated_by' => self::adminId(),
                ]);
            }
        }
    }


    public function createPlan($plans, $tipId)
    {
        if(is_array($plans)) {

            foreach ($plans as $key => $value) {

                if(!empty($value)) {

                    TipPlan::create([
                        'tip_id' => $tipId,
                        'plan_id' => $value
                    ]);
                }
            }
        }
    }


    /**
     * Filter by tip segment.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTipsegment($query, $segment)
    {
        if(!empty($segment)) {
            return $query->where('segment', $segment);
        }
    }

    /**
     * Filter by tip segment.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTipdatestartto($query, $start, $to)
    {
        if(!empty($start) && !empty($to)) {
            $start = Carbon::parse($start)->format('Y-m-d');
            $to = Carbon::parse($to)->format('Y-m-d');
            return $query->whereBetween('created_at', [$start, $to]);
        }
    }

    /**
     * Search partner by representative name or email.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchbymfapi($query, $mfApi)
    {
        if(!empty($mfApi)) {

                $query->whereHas('tip_mutual_funds', function ($query) use ($mfApi) {
                    //$query->whereJsonContains('mf_api->meta->scheme_category', $mfApi);
                    $query->whereJsonContains('mf_api->meta->scheme_code', $mfApi);
                    //$query->whereJsonContains('mf_api->meta->scheme_code', "%{$mfApi}%");
                    //$query->where('mf_api->scheme_category', $mfApi);
                });

            return $query;
        }

    }

    public function scopeCreatedAt($query, $created_at)
    {
        if(!empty($created_at)) {
            return $query->whereDate('created_at', $created_at);
        }
    }
}

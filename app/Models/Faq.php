<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    /**
     * @var string
     */
    protected $table = 'faqs';

    /**
     * @var array
     */
    protected $fillable = ['title', 'content', 'created_by'];
}

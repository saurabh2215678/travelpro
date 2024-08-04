<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Review extends Model{

    protected $table = 'reviews';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'product_id',
        'heading',
        'comment',
        'rating',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function reviewUser(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    /**
     * @return BelongsTo
     */
    public function reviewProduct(): BelongsTo
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

}
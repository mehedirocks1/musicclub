<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Optional if you follow Laravel convention 'branches')
     */
    protected $table = 'branches';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'map_url',
    ];

    /**
     * The attributes that should be cast to native types.
     * (Optional, useful if you want to cast JSON, dates, etc.)
     */
    protected $casts = [
        // 'map_url' => 'string', // not needed, already string
    ];

    /**
     * Indicates if the model should be timestamped.
     * Default is true, so you can remove this if you want
     */
    public $timestamps = true;
}

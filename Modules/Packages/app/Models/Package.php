<?php

namespace Modules\Packages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code','name','slug','status','price','currency','billing_period',
        'access_duration_days','sale_starts_at','sale_ends_at','image_path','promo_video_url',
        'summary','description','features','prerequisites','target_audience',
        'visibility','sort_order','instructor_id','category_id','enrollments_count',
        'rating_avg','tax_rate','is_discountable',
    ];

    protected $casts = [
        'features'              => 'array',
        'prerequisites'         => 'array',
        'sale_starts_at'        => 'datetime',
        'sale_ends_at'          => 'datetime',
        'price'                 => 'decimal:2',
        'rating_avg'            => 'decimal:2',
        'tax_rate'              => 'decimal:2',
        'is_discountable'       => 'boolean',
        'access_duration_days'  => 'integer',
        'enrollments_count'     => 'integer',
        'sort_order'            => 'integer',
    ];

    // শুধু slug binding — এটা স্ট্যান্ডার্ড, রেখে দিলাম
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

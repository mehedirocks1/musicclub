<?php

namespace Modules\Packages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction; 
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class Package extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'code','name','slug','status','price','currency','billing_period',
        'access_duration_days','sale_starts_at','sale_ends_at','image_path','promo_video_url',
        'summary','description','features','prerequisites','target_audience',
        'visibility','sort_order','instructor_id','category_id','enrollments_count',
        'rating_avg','tax_rate','is_discountable',
    ];

    protected $casts = [
        'features' => 'array',
        'prerequisites' => 'array',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
    ];
}
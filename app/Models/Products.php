<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Products extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'image_path',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}

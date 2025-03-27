<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Application extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const OCCUPANCY = 0;
    const NEW_BUSINESS = 1;
    const RENEWAL_BUSINESS = 2;

    const FSIC_TYPE = [
        self::OCCUPANCY,
        self::NEW_BUSINESS,
        self::RENEWAL_BUSINESS,
    ];

    protected $fillable = ['application_number', 'establishment_id', 'fsic_type', 'application_date'];

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function applicationStatuses(): HasMany
    {
        return $this->hasMany(ApplicationStatus::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function fsics(): HasMany
    {
        return $this->hasMany(Fsic::class);
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(200)
            ->height(200)
            ->optimize()
            ->performOnCollections('fsic_requirements');

        $this->addMediaConversion('web_thumbnail')
            ->width(360)
            ->height(360)
            ->optimize()
            ->performOnCollections('fsic_requirements');

        $this->addMediaConversion('responsive')
            ->height(720)
            ->width(720)
            ->withResponsiveImages()
            ->optimize()
            ->performOnCollections('fsic_requirements');
    }
}

// @foreach($application->getMedia('fsic_requirements') as $media)
//     <img src="{{ $media->getFullUrl('thumbnail') }}" class="img-fluid" alt="Application Thumbnail">
// @endforeach

// <img src="{{ $application->optimized_image }}" class="img-fluid" alt="Optimized Image">

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MediaImage extends Model implements HasMedia{
    use InteractsWithMedia;
    
    protected $table = 'media_manager';

    protected $guarded = ['id'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(150)
              ->height(150)
              ->sharpen(10)
              ->nonQueued();

        $this->addMediaConversion('medium')
              ->width(400)
              ->height(400)
              ->sharpen(10)
              ->nonQueued();
    }

}
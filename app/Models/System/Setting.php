<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\System\Setting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @mixin \Eloquent
 */
class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name',
        'key',
        'value',
        'locked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'locked',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
        'locked' => 'boolean',
    ];

    public static function lockedAttributesDisplayName(string $key): string
    {
        return [
            'shop_name' => __('Store name'),
            'shop_legal_name' => __('Store Legal name'),
            'shop_email' => __('Store email'),
            'shop_logo' => __('Store Logo'),
            'shop_cover' => __('Store Cover Image'),
            'shop_about' => __('Store description'),
            'shop_country_id' => __('Country'),
            'shop_currency_id' => __('Store Currency'),
            'shop_street_address' => __('Store street address'),
            'shop_zipcode' => __('Zip Code'),
            'shop_city' => __('Store city'),
            'shop_phone_number' => __('Store phone number'),
            'shop_lng' => __('Longitude'),
            'shop_lat' => __('Latitude'),
            'shop_facebook_link' => __('Facebook Page'),
            'shop_instagram_link' => __('Twitter account'),
            'shop_twitter_link' => __('Instagram account'),
            'google_analytics' => __('Google Analytics'),
        ][$key];
    }
}

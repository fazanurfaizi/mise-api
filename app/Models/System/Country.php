<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\System\Country
 *
 * @property int $id
 * @property string $name
 * @property string $name_official
 * @property string $cca2
 * @property string $cca3
 * @property string $flag
 * @property string $latitude
 * @property string $longitude
 * @property array $currencies
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCca2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCca3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNameOfficial($value)
 * @mixin \Eloquent
 */
class Country extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_official',
        'cca2',
        'cca3',
        'flag',
        'latitude',
        'longitude',
        'currencies'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'currencies' => 'array'
    ];
}

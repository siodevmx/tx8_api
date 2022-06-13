<?php

namespace App\Models;

use App\Traits\UUIDTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourPrice extends Model
{
    use HasFactory, UUIDTrait;

    public static string $timeZone = 'America/Mexico_City';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomenclature_product_id',
        'start_at',
        'finish_at',
        'price',
        'compare_at_price',
        'time_zone_id'
    ];


    public function timeZone()
    {
        $this->hasOne(TimeZone::class);
    }

    public function setStartAtAttribute($startAt)
    {
        $timestamp = "2021-11-05 {$startAt}";
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, static::$timeZone);
        $this->attributes['start_at'] = $date->setTimezone('UTC')->format('H:i:s');
    }

    public function setFinishAtAttribute($finishAt)
    {
        $timestamp = "2021-11-05 {$finishAt}";
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, static::$timeZone);
        $this->attributes['finish_at'] = $date->setTimezone('UTC')->format('H:i:s');
    }

    public function getStartAtAttribute($value)
    {
        $timestamp = "2021-11-05 {$value}";
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');
        return $date->setTimezone(static::$timeZone)->format('H:i:s');
    }

    public function getFinishAtAttribute($value)
    {
        $timestamp = "2021-11-05 {$value}";
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');
        return $date->setTimezone(static::$timeZone)->format('H:i:s');
    }

}

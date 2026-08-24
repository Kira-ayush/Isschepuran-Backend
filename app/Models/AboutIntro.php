<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutIntro extends Model
{
    protected $fillable = ['origin_title', 'origin_body', 'established_year', 'vision', 'mission'];

    /**
     * Always fetch (and lazily create) the single About intro row.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'origin_title' => 'Rising from Cyclone Yaas',
            'origin_body' => 'In May 2021, Cyclone Yaas devastated the coastlines of West Bengal and Odisha. What began as a spontaneous relief effort by a group of passionate individuals quickly transformed into a lifelong commitment. We witnessed firsthand the fragility of life in the Sundarbans. Ichhe Puran was founded not just to provide immediate aid, but to build lasting bridges toward economic independence and ecological security.',
            'established_year' => 2021,
            'vision' => 'To create a world where every community thrives in harmony with a restored environment, where green canopies shelter every home and education is the birthright of every child.',
            'mission' => 'To empower vulnerable coastal communities through climate-resilient livelihoods, education, and direct environmental stewardship.',
        ]);
    }
}

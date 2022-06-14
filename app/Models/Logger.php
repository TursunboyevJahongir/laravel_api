<?php

namespace App\Models;

use App\Core\Models\CoreModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logger extends CoreModel
{
    protected $table = 'logger';

    protected $fillable = ['ip',
                           'user_agent',
                           'user_id',
                           'action',
                           'uri',
                           'method',
                           'headers',
                           'payload',
                           'response',
                           'response_status',
                           'response_message'];

    protected $casts = [
        'date'     => 'datetime:Y-m-d H:i:s',
        'action'   => 'array',
        'headers'  => 'array',
        'payload'  => 'array',
        'response' => 'array',
    ];

    protected $dates = ['date'];

    public $timestamps = false;

    protected array $searchable = [
        'uri',
        'user_agent',
        'response_message',
    ];

    protected $appends = ['happened'];

    //show diff with date at now method Happened
    public function getHappenedAttribute()
    {
        return $this->date->diffForHumans();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

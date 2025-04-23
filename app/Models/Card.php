<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'card_number',
        'balance',
        'custom',
    ];

    protected $casts = [
        'balance' => 'float',
        'custom' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}

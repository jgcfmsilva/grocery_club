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

    public function operations()
    {
        return $this->hasMany(CardOperation::class);
    }

    public function increaseBalance(float $amount): bool
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('The value to add must be positive.');
        }

        $this->balance += $amount;
        return $this->save();
    }

    public function decreaseBalance(float $amount): bool
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('The amount to decrease must be positive.');
        }

        if ($this->balance < $amount) {
            throw new \RuntimeException('There are not enough funds on the virtual card');
        }

        $this->balance -= $amount;
        return $this->save();
    }

}

<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\QueuedVerifyEmail;
use App\Notifications\QueuedResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'blocked',
        'gender',
        'photo',
        'nif',
        'default_delivery_address',
        'default_payment_type',
        'default_payment_reference',
        'custom',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
            'custom' => 'array',
            'type' => UserType::class
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new QueuedResetPassword($token));
    }

    public function card()
    {
        return $this->hasOne(Card::class, 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isPendingMember() {
        return $this->type === UserType::PendingMember;
    }
    
    public function isMember() {
        return $this->type === UserType::Member;
    }
    
    public function isBoard() {
        return $this->type === UserType::Board;
    }
    
    public function isEmployee() {
        return $this->type === UserType::Employee;
    }

    public function isBlocked() {
        return (bool) $this->blocked;
    }
    
    public function isMemberOrBoard() {
        return in_array($this->type, [UserType::Member, UserType::Board]);
    }

    public function changeType(UserType $newType): bool
    {
        if ($this->type === $newType) {
            return false;
        }

        $this->type = $newType;
        return $this->save();
    }

}

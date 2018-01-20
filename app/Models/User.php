<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;

    protected $table = 'user';

    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'password',
        'active',
        'image_filename',
        'stripe_id',
        'stripe_key',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'active',
        'id',
    ];

    protected $appends = [
        'profile_image_url',
        'full_name_or_username',
    ];

    public function getRouteKeyName() {
        return 'username';
    }

    public function channels() {
        return $this->hasMany(Channel::class);
    }

    public function activationToken() {
        return $this->hasOne(ActivationToken::class);
    }

    public function passwordResetToken() {
        return $this->hasOne(PasswordReset::class);
    }

    public function passwordlessEntryToken() {
        return $this->hasOne(PasswordlessEntry::class);
    }

    public function videos() {
        return $this->hasManyThrough(Video::class, Channel::class);
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }

    public function subscribedChannels() {
        return $this->belongsToMany(Channel::class, 'subscription');
    }

    public function socialLogin() {
        return $this->hasMany(UserSocialLogin::class);
    }

    public function bought() {
        return $this->hasMany(Sale::class, 'buyer_id');
    }

    public function sold() {
        return $this->hasMany(Sale::class, 'seller_id');
    }

    public function getFullNameOrUsernameAttribute() {
        return $this->getNameOrUsername();
    }

    public function getName() {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }

        if ($this->first_name) {
            return $this->first_name;
        }

        return null;
    }

    public function getNameOrUsername() {
        return $this->getName() ?: $this->username;
    }

    public function getFirstNameOrUserName() {
        return $this->first_name ?: $this->username;
    }

    public function getUserAvatarUrl($size = 40) {
        $hash = md5($this->email);
        return  "https://www.gravatar.com/avatar/{$hash}?d=mm&s={$size}";
    }

    public function getProfileImageUrlAttribute() {
        return $this->getImageUrl();
    }

    public function getImageUrl() {
        if (!$this->image_filename) {
            return $this->getUserAvatarUrl(160);
        }

        return config('myengine.cdn.cloudfront.images') . 'user/' . $this->image_filename;
    }

    public static function byEmail($email) {
        return static::where('email', $email);
    }

    public function isActive() {
        return (bool) $this->active;
    }

    public function getAvatarUrl($size = 40) {
        $hash = md5($this->email);
        return  "https://www.gravatar.com/avatar/{$hash}?d=mm&s={$size}";
    }

    public function isVideoOwnedByUser(Video $video) {
        return $this->videos()->where('id', $video->id);
    }

    public function isUserSubscribedTo(Channel $channel) {
        return (bool) $this->subscriptions->where('channel_id', $channel->id)->count();
    }

    public function ownsChannel(Channel $channel) {
        return $this->id === $channel->user_id;
    }

    public function hasSocialLoginLinked($service) {
        return (bool) $this->socialLogin->where('login_service', $service)->count();
    }
}

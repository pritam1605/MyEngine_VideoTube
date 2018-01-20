<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy {
    use HandlesAuthorization;

    public function canViewAllUserVideos(User $user, User $sentUser) {
      return $user->id === $sentUser->id;
    }

    public function canViewAllUserChannels(User $user, User $sentUser) {
      return $user->id === $sentUser->id;
    }

    public function canViewUserProfile(User $user, User $sentUser) {
      return $user->id === $sentUser->id;
    }

    public function canEditUserProfile(User $user, User $sentUser) {
      return $user->id === $sentUser->id;
    }

    public function canViewPurchases(User $user, User $sentUser) {
      return $user->id === $sentUser->id;
    }

    public function canViewSubscriptions(User $user, User $sentUser) {
      return $user->id === $sentUser->id;
    }
}
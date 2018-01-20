<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy {
    use HandlesAuthorization;

    public function update(User $user, Video $video) {
        return $user->isVideoOwnedByUser($video);
   	}

   	public function delete(User $user, Video $video) {
        return $user->isVideoOwnedByUser($video);
   	}

   	public function canLogView(User $user, Video $video) {
        return $video->canBeAccessedByCurrentUser();
    }

    public function vote(User $user, Video $video) {

      //  user has to be signed in to vote
      if (!$user) {
        return false;
      }

      // the video must allow votes and the user must be able to access the video
      if (!$video->canVote() || !$video->canBeAccessedByCurrentUser()) {
        return false;
      }

      return true;
    }

    public function comment(User $user, Video $video) {

      //  user has to be signed in to comment
      if (!$user) {
        return false;
      }

      // the video must allow comment and the user must be able to access the video
      if (!$video->canComment() || !$video->canBeAccessedByCurrentUser()) {
        return false;
      }

      return true;
    }
}
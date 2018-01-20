<?php

namespace App\Traits;

trait VoteCount {

	public function getUpVotes() {
		return $this->votes()->where('type', 'up')->get();
	}

	public function getDownVotes() {
		return $this->votes()->where('type', 'down')->get();
	}
}
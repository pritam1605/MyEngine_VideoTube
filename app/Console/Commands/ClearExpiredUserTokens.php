<?php

namespace App\Console\Commands;

use App\Models\PasswordReset;
use App\Models\PasswordlessEntry;
use Illuminate\Console\Command;

class ClearExpiredUserTokens extends Command {

    protected $signature = 'token:clear-all';

    protected $description = 'Flush all expired user tokens';


    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        PasswordReset::expired()->delete();
        PasswordlessEntry::expired()->delete();
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupFirebaseUsers extends Command
{
    protected $signature = 'app:cleanup-firebase-users';
    protected $description = 'List and delete all Firebase Auth users';

    public function handle()
    {
        $auth = app('firebase.auth');

        $this->info('Fetching all Firebase Auth users...');

        $users = $auth->listUsers();
        $count = 0;
        $uids = [];

        foreach ($users as $user) {
            $count++;
            $uids[] = $user->uid;
            $this->line("  Found user: {$user->email} (UID: {$user->uid})");
        }

        $this->info("\nTotal users found: {$count}");

        if ($count === 0) {
            $this->info('No users to delete. Firebase Auth is already clean!');
            return;
        }

        $this->info("Deleting all {$count} users...");

        foreach ($uids as $uid) {
            $auth->deleteUser($uid);
            $this->line("  Deleted: {$uid}");
        }

        $this->info("\nDone! All {$count} Firebase users have been deleted.");
    }
}

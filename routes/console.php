<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\BackupService;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('backup:daily', function (BackupService $backupService) {
    $this->info('Starting daily database backup...');
    
    $result = $backupService->createBackup();
    
    if ($result['success']) {
        $this->info($result['message']);
        $this->info('Backup created: ' . $result['file']);
    } else {
        $this->error('Backup failed: ' . $result['message']);
    }
})->purpose('Create a daily database backup (Data Only)')
  ->daily();


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\BackupService;

class BackupController extends Controller
{
    private function authorizeAdmin()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user || !($user->hasRole('Admin') || $user->hasRole('Super Admin'))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        // Ensure backups directory exists
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        $files = Storage::files('backups');
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => $this->formatSize(Storage::size($file)),
                'date' => Carbon::createFromTimestamp(Storage::lastModified($file))->format('Y-m-d H:i:s'),
                'path' => $file
            ];
        }

        // Sort by date desc
        usort($backups, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return view('backup.index', compact('backups'));
    }

    public function store(BackupService $backupService)
    {
        $this->authorizeAdmin();

        $result = $backupService->createBackup();

        if ($result['success']) {
            return redirect()->route('backup.index')->with('success', $result['message']);
        } else {
            return redirect()->route('backup.index')->with('error', 'Backup failed: ' . $result['message']);
        }
    }

    public function download($filename)
    {
        $this->authorizeAdmin();

        if (Storage::exists('backups/' . $filename)) {
            return Storage::download('backups/' . $filename);
        }
        return redirect()->route('backup.index')->with('error', 'File not found.');
    }

    public function destroy($filename)
    {
        $this->authorizeAdmin();

        if (Storage::exists('backups/' . $filename)) {
            Storage::delete('backups/' . $filename);
            return redirect()->route('backup.index')->with('success', 'Backup deleted successfully.');
        }
        return redirect()->route('backup.index')->with('error', 'File not found.');
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
}

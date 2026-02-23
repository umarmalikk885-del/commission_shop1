<?php

namespace App\Http\Controllers;

use App\Models\RowBackup;
use App\Services\RowBackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RowBackupController extends Controller
{
    public function restore(RowBackup $backup, RowBackupService $service): RedirectResponse
    {
        $user = Auth::user();
        if (!$user instanceof \App\Models\User || !($user->hasRole('Admin') || $user->hasRole('Super Admin'))) {
            abort(403);
        }

        $service->restore($backup);

        return back()->with('success', 'Row restored from backup.');
    }
}

<?php

namespace App\Services;

use App\Models\RowBackup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RowBackupService
{
    public function backupModel(Model $model, string $operationType = 'insert'): void
    {
        if ($model instanceof RowBackup) {
            return;
        }

        $data = $model->getAttributes();
        $table = $model->getTable();
        $recordId = $data[$model->getKeyName()] ?? null;
        $insertedAt = $data['created_at'] ?? now();
        $userId = Auth::id();

        RowBackup::create([
            'table_name' => $table,
            'record_id' => $recordId,
            'data' => $data,
            'operation_type' => $operationType,
            'user_id' => $userId,
            'inserted_at' => $insertedAt,
        ]);
    }

    public function restore(RowBackup $backup): void
    {
        $data = $backup->data ?? [];
        if (!is_array($data)) {
            $data = (array) $data;
        }

        $table = $backup->table_name;
        $recordId = $backup->record_id;

        if ($recordId) {
            DB::table($table)->updateOrInsert(['id' => $recordId], $data);
        } else {
            DB::table($table)->insert($data);
        }
    }
}


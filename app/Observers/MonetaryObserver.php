<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasMonetaryColumns;
use App\Models\BakriBook;

class MonetaryObserver
{
    protected function sanitize(Model $model): void
    {
        $columns = method_exists($model, 'monetaryColumns') ? $model::monetaryColumns() : [];
        foreach ($columns as $col) {
            if (!$model->isFillable($col) && !array_key_exists($col, $model->getAttributes())) {
                continue;
            }
            $val = $model->{$col} ?? 0;
            if ($val === null || !is_numeric($val) || $val < 0) {
                $model->{$col} = 0.00;
            } else {
                $model->{$col} = round((float)$val, 2);
            }
        }

        if ($model instanceof BakriBook) {
            $model->calculateTotalExpenses();
            $model->calculateNetGoat();
            $model->total_expenses = round(max(0, (float)$model->total_expenses), 2);
            $model->net_goat = round(max(0, (float)$model->net_goat), 2);
        }
    }

    public function creating(Model $model): void
    {
        $this->sanitize($model);
    }

    public function saving(Model $model): void
    {
        $this->sanitize($model);
    }
}

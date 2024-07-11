<?php

namespace App\Observers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DBChangeObserver
{
    public function created(Model $model)
    {
        foreach ($model->getAttributes() as $key => $value) {
            DB::table('db_changes_log')->insert([
                'table_name' => $model->getTable(),
                'row_id' => $model->getKey(),
                'column_name' => $key,
                'old_value' => null,
                'new_value' => $value,
                'operation' => 'created',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function updated(Model $model)
    {
        $dirty = $model->getDirty();
        foreach ($dirty as $key => $newValue) {
            $oldValue = $model->getOriginal($key);
            DB::table('db_changes_log')->insert([
                'table_name' => $model->getTable(),
                'row_id' => $model->getKey(),
                'column_name' => $key,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'operation' => 'updated',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function deleted(Model $model)
    {
        foreach ($model->getAttributes() as $key => $value) {
            DB::table('db_changes_log')->insert([
                'table_name' => $model->getTable(),
                'row_id' => $model->getKey(),
                'column_name' => $key,
                'old_value' => $value,
                'new_value' => null,
                'operation' => 'deleted',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

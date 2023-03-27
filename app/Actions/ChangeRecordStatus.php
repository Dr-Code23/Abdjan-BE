<?php

namespace App\Actions;

class ChangeRecordStatus
{

    /**
     * @param string $model
     * @param int $id
     * @param bool $value
     * @param string $columnName
     * @return bool
     */
    public function handle(string $model , int $id, bool $value, string $columnName = 'status'): bool
    {
        $record = (new ("App\\Models\\$model"))::where('id' , $id)->first();
        if($record){
            $record->update([$columnName => (int)$value]);

            return true;
        }
        return false;
    }
}

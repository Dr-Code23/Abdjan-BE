<?php

namespace App\Actions;

use App\Models\User;

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
        $record = (new $model)::where('id' , $id)->first();
        if($record){
            if($record instanceof User && $record->hasRole('super_admin')){
                return false;
            }
            $record->update([$columnName => (int)$value]);

            return true;
        }

        return false;
    }
}

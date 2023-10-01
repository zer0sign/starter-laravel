<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel, WithStartRow, WithMapping
{
    public function startRow(): int
    {
        return 2; // Start reading data from row 2
    }

    public function map($row): array
    {
        return [
            'nama' => $row[0],
            'username' => $row[1],
            'password' => intval($row[2]), // Hash the password
        ];
    }

    public function model(array $row)
    {
        return new User([
            'nama' => $row['nama'],
            'username' => $row['username'],
            'password' => bcrypt($row['password']), // Hash the password
        ]);
    }

    /**
     * This method will be called when the import is completed.
     */
    public function onFinish()
    {
        // Commit the database transaction
        DB::commit();
    }

    /**
     * This method will be called if the import fails.
     *
     * @param \Throwable $e
     */
    public function onFailure(\Throwable $e)
    {
        // Rollback the database transaction in case of failure
        DB::rollBack();
    }

    /**
     * This method will be called before starting the import.
     */
    public function beforeImport()
    {
        // Start a new database transaction before the import begins
        DB::beginTransaction();
    }
}

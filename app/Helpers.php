<?php

use App\Models\ActivityLog; 

if (!function_exists('createLog')) {
    function createLog($username,$activity)
    {
        $data = [
            'username' => $username,
            'activity' => $activity, // Sesuaikan dengan jenis aktivitas Anda
        ];
        ActivityLog::create($data);
    }
}

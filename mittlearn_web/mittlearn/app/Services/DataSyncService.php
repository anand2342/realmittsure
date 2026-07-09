<?php

namespace App\Services;

use App\Models\erpSync\SyncLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DataSyncService
{
    public function syncUsers()
    {
        $dataSyncFrom = '2020-11-01 00:01:00';
        // $erpUsers = DB::connection('erp')->table('all_user')->where('status', 'active')->where('update_time', '>', $dataSyncFrom)->get();
        $erpUsers = DB::connection('erp')
            ->table('all_user')
            ->join('add_school', 'all_user.schid', '=', DB::raw('add_school.id'))
            ->where('all_user.status', 'active')
            ->where('all_user.update_time', '>', $dataSyncFrom)
            // ->where('all_user.user_type', 'student')
            // ->limit(2)
            ->get();
        if ($erpUsers) {
            foreach ($erpUsers as $erpUser) {
                try {

                    $user = User::updateOrCreate(
                        ['username' => $erpUser->name],
                        [
                            'username'               => $erpUser->username,
                            'mobile_no'               => $erpUser->mobile,
                            'password'               => Hash::make($erpUser->password),
                            'validate_string'               => $erpUser->password,
                            'validate_string'               => $erpUser->password,
                        ]
                    );
                    // Log sync success
                    SyncLog::create([
                        'table'     => 'user',
                        'table_id'  => $erpUser->id,
                        'data'      => json_encode($erpUser),
                        'status'    => 'synced',
                        'synced_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // Log sync failure
                    SyncLog::create([
                        'table'     => 'user',
                        'table_id'  => $erpUser->id,
                        'data'      => json_encode($erpUser),
                        'status'    => 'syncedError',
                        'synced_at' => now(),
                    ]);
                }
            }
        }
    }
}

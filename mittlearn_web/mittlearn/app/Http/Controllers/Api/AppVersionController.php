<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;

use App\Models\AppVersion;
use App\Services\ApiResponseService;

// Controller for handling app version related APIs
class AppVersionController extends BaseController
{

    public function getBaseUrl(Request $request)
    {
        // Returns the base URL and API base URL for the application
        $baseUrl = url('/');
        $apiBaseUrl = url('/api');
        $data = [
            'base_url' => $baseUrl . '/',
            'api_base_url' => $apiBaseUrl . '/',
        ];
        return $this->sendSuccess($data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
    }
    public function storeOrUpdateAppVersion(Request $request)
    {
        // Creates or updates the app version for a given platform (android/ios)
        $request->validate([
            'platform'     => 'required|in:android,ios',
            'version'      => 'required',
            'version_code'      => 'required',
            'update_note'  => 'nullable',
            'force_update' => 'nullable',
        ]);

        // Update or create the app version record for the platform
        $appVersion = AppVersion::updateOrCreate(
            ['platform' => $request->platform], // update condition
            [
                'version'      => $request->version,
                'version_code'      => $request->version_code,
                'update_note'  => $request->update_note,
                'force_update' => $request->force_update ?? 0,
            ]
        );
        // Return success response with the app version data
        return $this->sendSuccess($appVersion, config('constants.API_MSG.REC_ADD_SUCCESS'));
    }
    public function getAppVersion(Request $request)
    {
        // Gets the latest app version for the requested platform
        $request->validate([
            'platform' => 'required',
        ]);

        // Fetch the latest version for the given platform
        $version = AppVersion::where('platform', $request->platform)->latest()->first();

        // If no version found, return error response
        if (!$version) {
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        }

        // Return success response with the version data
        return $this->sendSuccess($version, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
    }
}

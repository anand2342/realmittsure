<?php

namespace App\Http\Controllers\userPortal;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\State;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{

    public $data = [];

    public function uploadProfileImage(Request $request)
    {
        try {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $user = auth()->user();
            if ($user && $user->image && Storage::disk('public')->exists('uploads/user/profile_image/' . $user->image)) {
                Storage::disk('public')->delete('uploads/user/profile_image/' . $user->image);
            }
            $profileImage = $request->file('profile_image');
            $extension = $profileImage->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $filePath = 'uploads/user/profile_image/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($profileImage));
            $user->image = $fileName;
            $user->save();

            return response()->json([
                'success' => true,
                'filePath' => asset('storage/' . $filePath),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'password' => 'required', // Current password
                'newpassword' => 'required|min:8|confirmed', // New password with confirmation
            ]);

            // Get the currently authenticated user
            $user = Auth::user();

            // Verify the current password
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'The current password is incorrect.']);
            }

            // Check if the new password is the same as the old password
            if (Hash::check($request->newpassword, $user->password)) {
                return back()->withErrors(['newpassword' => 'The new password cannot be the same as the current password.']);
            }

            // Update the user's password
            $user->password = Hash::make($request->newpassword);
            $user->validate_string = $request->newpassword;
            $user->save();

            // Return success response
            return back()->with('success', 'Password successfully changed!');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function updateProfileDetails(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            // 'dob' => 'required|date|before:today',
        ]);
        try {
            $userId = $request->input('id');
            $studentDetails = StudentDetails::where('user_id', $userId)->first();
            $userAdditonalDetails = UserAdditionalDetail::where('user_id', $userId)->first();
            // dd($userId, $studentDetails, $userAdditonalDetails);

            $user = User::find($userId);
            if (!$studentDetails) {
                return redirect()->route('up.dashboard')->with('error', 'No details found for the Student.');
            }
            if ($studentDetails) {
                $studentDetails->dob = $request->dob;
                $studentDetails->class = $request->class;
                $studentDetails->address = $request->address;
                $studentDetails->postal_code = $request->postal_code;
                $studentDetails->state = $request->state;
                $studentDetails->city = $request->city;
                $studentDetails->save();
            }
            if ($userAdditonalDetails) {
                $userAdditonalDetails->address = $request->address;
                $userAdditonalDetails->postal_code = $request->postal_code;
                $userAdditonalDetails->state = $request->state;
                $userAdditonalDetails->city = $request->city;
                $userAdditonalDetails->save();
            } else {
                $newdetail = new UserAdditionalDetail;
                $newdetail->user_id = $userId;
                $newdetail->address = $request->address;
                $newdetail->postal_code = $request->postal_code;
                $newdetail->state = $request->state;
                $newdetail->city = $request->city;
                $newdetail->save();
            }
            if ($user) {
                $user->name = $request->name;
                $user->save();
            }
            return redirect()->route('up.dashboard')->with('success', 'Your Data updated successfully');
        } catch (\TypeError $e) {
            return redirect()->route('up.dashboard')->with('error', 'A type error occurred while updating your data. Please try again.');
        } catch (\Exception $e) {
            return redirect()->route('up.dashboard')->with('error', 'An error occurred while updating your data. Please try again.');
        }
    }

    public function updateProfileAddress(Request $request)
    {
        try {
            $request->validate([
                // 'website' => 'nullable|url',
                'postal_code' => 'required|string|max:20',
                'state' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ]);
            $userId = $request->input('id');
            $schoolDetails = Schools::where('user_id', $userId)->first();
            if (!$schoolDetails) {
                return redirect()->route('sp.dashboard')->with('error', 'No details found for the selected school.');
            }

            if ($schoolDetails) {
                $schoolDetails->postal_code = $request->postal_code;
                $schoolDetails->state = $request->state;
                $schoolDetails->city = $request->city;
                $schoolDetails->address = $request->address; // Convert array to comma-separated string
                $schoolDetails->save();
            }
            return redirect()->route('sp.dashboard')->with('success', 'Your Data updated successfully');
        } catch (\TypeError $e) {
            return redirect()->route('sp.dashboard')->with('error', 'A type error occurred while updating your data. Please try again.');
        } catch (\Exception $e) {
            return redirect()->route('sp.dashboard')->with('error', 'An error occurred while updating your data. Please try again.');
        }
    }
}

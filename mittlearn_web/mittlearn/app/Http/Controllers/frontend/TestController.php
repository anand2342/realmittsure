<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\OtpSession;
use App\Models\StudentDetails;
use App\Models\TalentSkillQrCourse;
use App\Models\UserAdditionalDetail;
use App\Models\UserClass;
use App\Models\UserRole;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TestController extends Controller
{
    private array $data = [];
    // for talent and skil qr
    public function talentAndSkillQrRegister($category, $course_ids)
    {

        $category = base64_decode($category);
        $course_ids = base64_decode($course_ids);
        $course_ids = explode(',', $course_ids);

        // dd($course_ids);

        $this->data['matchedCategory'] = Category::where('status', 1)->where('parent_id', 2)->where('id', $category)
            ->first();


        if (!$this->data['matchedCategory']) {
            abort(404, 'Category not found');
        }

        // $this->data['matchedCourses'] = Course::whereIn('id', $course_ids)->first();
        $this->data['matchedCourses'] = Course::whereIn('id', $course_ids)->get();
        $this->data['userForm'] = 'd2c_user';

        if (!$this->data['matchedCourses']) {
            abort(404, 'Courses not found');
        }

        return view("otherUsers.talentAndSkillQrRegister", $this->data);
    }

    public function talentAndSkillQrRegisterSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|min:10|numeric',
            'email' => 'nullable|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'sometimes|min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8',
            'captcha' => 'required|captcha',
        ], ['captcha.captcha' => 'Invalid captcha code.']);

        try {
            if (!$request->terms_accepted) {
                return redirect()->back()->with(['error' => 'You must accept the terms and conditions.']);
            }

            // Fetch user if exists
            $user = User::where(function ($q) use ($request) {
                $q->where('mobile_no', $request->mobile);
                if ($request->email) {
                    $q->orWhere('email', $request->email);
                }
            })
                ->orderBy('id', 'DESC')
                ->first();

            if ($user) {

                // ================= USER EXISTS BUT NOT VERIFIED =================
                if ($user->is_mobile_verified == 0) {

                    // ✅ Store in new table
                    TalentSkillQrCourse::create([
                        'user_role'      => 'd2c_user',
                        'user_id'        => $user->id,
                        'category_id'    => 2,
                        'subcategory_id' => $request->sub_category_id,
                        'course_ids'     => $request->course_ids,
                    ]);

                    // OTP flow
                    $otp = rand(100000, 999999);
                    session(['otp_value' => $otp, 'id' => $user->mobile_no]);

                    OtpSession::updateOrCreate(
                        ['session_id' => $user->id],
                        [
                            'otp' => $otp,
                            'mobile_email' => $user->mobile_no,
                            'expire_at' => now()->addMinutes(10),
                        ]
                    );

                    $sent = sendSms($user->mobile_no, $otp, 'User');

                    if (!$sent) {
                        return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
                    }

                    return view("auth.mobile-verify", [
                        'data' => $user->mobile_no,
                        'userForm' => 'd2c_user'
                    ]);
                }

                // ================= USER VERIFIED =================

                // ✅ Store in new table
                TalentSkillQrCourse::create([
                    'user_role'      => 'd2c_user',
                    'user_id'        => $user->id,
                    'category_id'    => 2,
                    'subcategory_id' => $request->sub_category_id,
                    'course_ids'     => $request->course_ids,
                ]);

                // Update password if provided
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                    $user->validate_string = $request->password;
                    $user->save();
                }

                Auth::login($user);

                $landingUi = getUserClassLandingUi();

                if ($landingUi == 'mittbunny') {
                    $this->storeStudentClass();
                    return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
                } else {
                    $this->storeStudentOverview($request);
                    return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
                }
            }

            // ================= NEW USER =================

            $data = new User;
            $data->name = $request->name;
            $data->mobile_no = $request->mobile;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->validate_string = $request->password;
            $data->status = 1;
            $data->user_type = 'd2c_user';
            $data->is_verified = 1;
            $data->category_id = $request->category_id; // it is the sub category id (as category is talent and skill and sub category is dance so here the id is of dance)
            $data->source = 'd2c_qr_code';
            $data->save();

            UserRole::create([
                'user_id' => $data->id,
                'role_slug' => 'd2c_user'
            ]);

            UserAdditionalDetail::create([
                'user_id' => $data->id,
                'role' => 'd2c_user'
            ]);

            // ✅ Store in new table
            TalentSkillQrCourse::create([
                'user_role'      => 'd2c_user',
                'user_id'        => $data->id,
                'category_id'    => 2,
                'subcategory_id' => $request->sub_category_id,
                'course_ids'     => $request->course_ids,
            ]);

            // OTP flow
            $otp = rand(100000, 999999);
            session(['otp_value' => $otp, 'id' => $data->mobile_no]);

            OtpSession::updateOrCreate(
                ['session_id' => $data->id],
                [
                    'otp' => $otp,
                    'mobile_email' => $data->mobile_no,
                    'expire_at' => now()->addMinutes(10),
                ]
            );

            $sent = sendSms($data->mobile_no, $otp, 'User');

            if (!$sent) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }

            return view("auth.mobile-verify", [
                'data' => $data->mobile_no,
                'userForm' => 'd2c_user'
            ]);
        } catch (\Exception $e) {
            \Log::error('D2C QR Registration Error: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Something went wrong']);
        }
    }
                         
    public function sms(Request $request)
    {
        $user = User::where('mobile_no', '8696259964')->first();
        $sent = sendSms($user->mobile_no, '', $user);
        if (!$sent) {
            dd('error', 'Failed to send OTP. Please try again.');
        }
    }
    public function email(Request $request)
    {
        $user = User::find(612);
        $templateId = 18;
        $data = [
            'NAME' => 'abc',
            'EMAIL' => 'abc',
            'PASSWORD' => 'abc',
        ];
        if ($user) {
            if (sendEmail($templateId, 'krishan.gopal@qdegrees.com', $data)) {
                dd('Email sent successfully');
            } else {
                dd('Email sending failed');
            }
        }
    }


    public function multiSelect()
    {
        return view('test');
    }
    public function sampleQrCode()
    {
        // 1. Generate QR Code (500x500)
        $qrCode = QrCode::format('png')
            ->size(500)
            ->errorCorrection('H') // High error correction for better logo visibility
            ->generate('https://mittlearn.com/qr-code');

        // 2. Create QR image resource
        $qrImage = imagecreatefromstring($qrCode);
        $logoPath = public_path('frontend/images/mittlearn-logo.svg');

        if (!file_exists($logoPath)) {
            throw new \Exception("Logo file not found");
        }

        $logo = imagecreatefrompng($logoPath);

        // Get dimensions
        $qrWidth = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);
        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);

        // 3. Resize logo to 40% of QR size
        $newLogoWidth = $qrWidth * 0.4;
        $newLogoHeight = $logoHeight * ($newLogoWidth / $logoWidth);

        $resizedLogo = imagecreatetruecolor($newLogoWidth, $newLogoHeight);
        imagealphablending($resizedLogo, false);
        imagesavealpha($resizedLogo, true);

        imagecopyresampled(
            $resizedLogo,
            $logo,
            0,
            0,
            0,
            0,
            $newLogoWidth,
            $newLogoHeight,
            $logoWidth,
            $logoHeight
        );

        // OPTIONAL: Apply mild sharpening
        $sharpenMatrix = [
            [-1, -1, -1],
            [-1, 16, -1],
            [-1, -1, -1]
        ];
        imageconvolution($resizedLogo, $sharpenMatrix, 8, 0); // Normalize with divisor = 8

        $padding = 6;
        $x = ($qrWidth - $newLogoWidth) / 2;
        $y = ($qrHeight - $newLogoHeight) / 2 - 25;

        $bgColor = imagecolorallocate($qrImage, 255, 255, 255);
        imagefilledrectangle(
            $qrImage,
            $x - $padding,
            $y - $padding,
            $x + $newLogoWidth + $padding,
            $y + $newLogoHeight + $padding + 30,
            $bgColor
        );

        imagecopy(
            $qrImage,
            $resizedLogo,
            $x,
            $y,
            0,
            0,
            $newLogoWidth,
            $newLogoHeight
        );

        $fontSize = 6;
        $textLines = [
            'LKG',
        ];

        $lineSpacing = 13;
        $startY = $y + $newLogoHeight + 4;

        $textColor = imagecolorallocate($qrImage, 0, 0, 0); // Black

        foreach ($textLines as $index => $line) {
            $textWidth = imagefontwidth($fontSize) * strlen($line);
            $textX = ($qrWidth - $textWidth) / 2;
            $textY = $startY + ($index * $lineSpacing);
            imagestring($qrImage, $fontSize, $textX, $textY, $line, $textColor);
        }

        ob_start();
        imagepng($qrImage, null, 9);
        $mergedImage = ob_get_clean();

        imagedestroy($qrImage);
        imagedestroy($logo);
        imagedestroy($resizedLogo);

        return view('test', ['qrCodeBase64' => base64_encode($mergedImage)]);
    }


    public function getLatLngFromAddress($address, $apiKey)
    {
        $address = urlencode($address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Optional, depending on server

        $response = curl_exec($ch);
        dd($response);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $json = json_decode($response, true);

        if (isset($json['status']) && $json['status'] == 'OK') {
            $lat = $json['results'][0]['geometry']['location']['lat'];
            $lng = $json['results'][0]['geometry']['location']['lng'];
            return ['lat' => $lat, 'lng' => $lng];
        }

        return false;
    }
    public function getLatLong()
    {
        $apiKey = 'AIzaSyBk82ve6Lbv8sm0F0QGffeIiNOuHbmgV5M';
        $location = $this->getLatLngFromAddress('Jawahar Lal Nehru Marg, D-Block, Malviya Nagar, Jaipur', $apiKey);

        if ($location) {
            dd("Latitude: " . $location['lat'] . ", Longitude: " . $location['lng']);
        } else {
            dd("Location not found.");
        }
        dd("hhhh Location not found.");

        // return $request;
    }
}

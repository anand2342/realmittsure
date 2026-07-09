<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Api\BaseController;
use App\Models\Category;
use App\Models\Classes;
use App\Models\D2cDigitalContent;
use App\Models\Medium;
use App\Models\UserClass;
use App\Models\Course;
use App\Models\TalentSkillQrCourse;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrCodeController extends BaseController
{
    public function assignContentFromQrUrl(Request $request)
    {
        try {
            $request->validate([
                'qr_url' => 'required',
            ]);

            $user = auth()->user();

            if (!$user) {
                return $this->sendError(
                    'User not authenticated',
                    'User not authenticated',
                    401
                );
            }

            // Parse URL
            $parsedUrl = parse_url($request->qr_url);

            $path = $parsedUrl['path'] ?? '';

            $segments = array_values(
                array_filter(explode('/', trim($path, '/')))
            );

            // Extract SN from query string if present, otherwise default to 1
            $sn = 1;
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
                $sn = (int) ($queryParams['msn'] ?? 1);
            }


            /**
             * Possible formats:
             * 1) /{category}/{class}?sn=X
             * 2) /{category}/{medium}/{class}?sn=X
             * 3) /{category}/{class}/{sn}
             * 4) /{category}/{medium}/{class}/{sn}
             */

            if (count($segments) < 2) {

                return $this->sendError(
                    'This QR code is not associated with The Mittsure book. Please scan a valid book QR code to continue.',
                    'This QR code is not associated with The Mittsure book. Please scan a valid book QR code to continue.',
                    422
                );
            }
            // =========================
            // TALENT-SKILLS QR SUPPORT
            // =========================

            // =========================
            // TALENT-SKILLS QR SUPPORT
            // =========================

            if ($segments[0] === 'tands') {

                /**
                 * Format:
                 * /tands/{base64(sub_category_id)}/{base64(course_ids)}
                 */

                if (count($segments) !== 3) {
                    return $this->sendError(
                        'Invalid Talent-Skills QR code',
                        'Invalid Talent-Skills QR code',
                        422
                    );
                }

                [, $encodedSubCategory, $encodedCourses] = $segments;

                // Decode sub category id
                $subCategoryId = base64_decode($encodedSubCategory, true);

                if (!$subCategoryId) {
                    return $this->sendError(
                        'Invalid Talent-Skills category',
                        'Invalid Talent-Skills category',
                        422
                    );
                }

                // Decode courses
                $decodedCourses = base64_decode($encodedCourses, true);

                if (!$decodedCourses) {
                    return $this->sendError(
                        'Invalid Talent-Skills courses',
                        'Invalid Talent-Skills courses',
                        422
                    );
                }

                $courseIds = explode(',', $decodedCourses);

                // Fixed main category
                $matchedCategory = Category::find(2);

                if (!$matchedCategory) {
                    return $this->sendError(
                        'Talent-Skills category not found',
                        'Talent-Skills category not found',
                        422
                    );
                }

                // Sub category
                $matchedSubCategory = Category::find($subCategoryId);

                if (!$matchedSubCategory) {
                    return $this->sendError(
                        'Talent-Skills sub-category not found',
                        'Talent-Skills sub-category not found',
                        422
                    );
                }

                // Check already assigned
                $alreadyAssigned = TalentSkillQrCourse::where('user_id', $user->id)
                    ->where('subcategory_id', $subCategoryId)
                    ->first();

                if (!$alreadyAssigned) {

                    TalentSkillQrCourse::create([
                        'user_role'      => 'd2c_user',
                        'user_id'        => $user->id,
                        'category_id'    => 2,
                        'subcategory_id' => $subCategoryId,
                        'course_ids'     => implode(',', $courseIds),
                    ]);
                }

                // Get courses
                $courses = Course::whereIn('id', $courseIds)->get();

                return $this->sendSuccess(
                    [
                        'category' => $matchedCategory->name,
                        'sub_category' => $matchedSubCategory->name,
                        'courses' => $courses,
                    ],
                    'Talent-Skills QR content successfully added to your account'
                );
            } else {

                // Check if last segment is numeric (SN in path)
                if (is_numeric(end($segments))) {
                    $sn = (int) array_pop($segments);
                }

                // Now parse remaining segments
                if (count($segments) === 2) {
                    // /category/class
                    [$categorySlug, $classEncoded] = $segments;
                    $mediumName = null;
                } elseif (count($segments) === 3) {
                    // /category/medium/class
                    [$categorySlug, $mediumName, $classEncoded] = $segments;
                }

                // ---------- CATEGORY ----------
                $matchedCategory = Category::where('status', 1)
                    ->where('parent_id', 1)
                    ->get()
                    ->firstWhere(function ($cat) use ($categorySlug) {
                        return Str::slug(
                            Str::substr(preg_replace('/[^a-zA-Z]/', '', $cat->name), 0, 4)
                        ) === $categorySlug;
                    });

                if (!$matchedCategory) {
                    return $this->sendError(
                        'Category not found',
                        'Category not found',
                        422
                    );
                }

                // ---------- CLASS ----------
                // Handle both standard and URL-safe base64
                $classEncoded = str_pad(strtr($classEncoded, '-_', '+/'), strlen($classEncoded) % 4, '=', STR_PAD_RIGHT);
                $className = base64_decode($classEncoded, true);

                if (!$className) {
                    return $this->sendError(
                        'Invalid class token',
                        'Invalid class token',
                        422
                    );
                }

                $matchedClass = Classes::where('name', $className)->first();

                if (!$matchedClass) {
                    return $this->sendError(
                        'Class not found',
                        'Class not found',
                        422
                    );
                }

                // ---------- MEDIUM (OPTIONAL) ----------
                $matchedMedium = null;
                if (!empty($mediumName)) {
                    $matchedMedium = Medium::where('name', $mediumName)->first();
                }

                // ---------- CHECK EXISTING MAPPING ----------
                $query = UserClass::where('user_id', $user->id)
                    ->where('class_id', $matchedClass->id)
                    ->where('category_id', $matchedCategory->id)
                    ->where('sn', $sn); // Add SN check

                if ($matchedMedium) {
                    $query->where('medium_id', $matchedMedium->id);
                } else {
                    $query->whereNull('medium_id');
                }

                if ($query->exists()) {
                    return $this->sendSuccess(
                        [],
                        'Content already mapped to this user'
                    );
                }

                // ---------- VERIFY D2C CONTENT EXISTS ----------
                $d2cContentQuery = D2cDigitalContent::where('sub_category_id', $matchedCategory->id)
                    ->where('class_id', $matchedClass->id)
                    ->where('sn', $sn);

                if ($matchedMedium) {
                    $d2cContentQuery->where('medium_id', $matchedMedium->id);
                } else {
                    $d2cContentQuery->whereNull('medium_id');
                }

                $d2cContent = $d2cContentQuery->first();

                if (!$d2cContent) {
                    return $this->sendError(
                        'No content found for this QR code',
                        'No content found for this QR code',
                        422
                    );
                }

                // ---------- ASSIGN CONTENT ----------
                $userClass = UserClass::create([
                    'user_id'     => $user->id,
                    'class_id'    => $matchedClass->id,
                    'category_id' => $matchedCategory->id,
                    'medium_id'   => $matchedMedium->id ?? null,
                    'user_role'   => 'd2c_user',
                    'sn'          => $sn, // Save SN
                ]);

                // Get course details
                $courses = [];
                if ($d2cContent->course_id) {
                    $courseIds = is_string($d2cContent->course_id)
                        ? explode(',', $d2cContent->course_id)
                        : (array) $d2cContent->course_id;

                    $courses = Course::whereIn('id', $courseIds)
                        ->get();
                }

                return $this->sendSuccess(
                    [
                        'user_class' => $userClass,
                        'category' => $matchedCategory->name,
                        'class' => $matchedClass->name,
                        'medium' => $matchedMedium ? $matchedMedium->name : null,
                        'sn' => $sn,
                        'courses' => $courses,
                    ],
                    'QR content successfully added to your account'
                );
            }
        } catch (ValidationException $e) {
            \Log::error('QR Code Assignment Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'qr_url' => $request->qr_url ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return $this->sendError(
                $e->errors(),
                'An error occurred while processing your request',
                422
            );
        } catch (Exception $e) {
            \Log::error('QR Code Assignment Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'qr_url' => $request->qr_url ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return $this->sendError(
                config('constants.API_MSG.SERVER_ERROR'),
                'An error occurred while processing your request',
                500
            );
        }
    }
}

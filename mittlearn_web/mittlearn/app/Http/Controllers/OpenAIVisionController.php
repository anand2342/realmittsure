<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenAIVisionController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $image  = $request->file('image');
        $base64 = base64_encode(file_get_contents($image->getRealPath()));
        $prompt = $request->text;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/responses', [
            "model" => "gpt-4.1-mini",
            "input" => [
                [
                    "role"    => "user",
                    "content" => [
                        [
                            "type" => "input_text",
                            "text" => $prompt,
                        ],
                        [
                            "type"      => "input_image",
                            "image_url" => "data:image/jpeg;base64," . $base64,
                        ],
                    ],
                ],
            ],
        ]);

        return response()->json($response->json());
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MenuScannerService
{
    /**
     * Send an image to Gemini Vision API and extract menu items.
     *
     * @param string $base64Image The base64 encoded image string (without data:image/... prefix).
     * @param string $mimeType The mime type of the image (e.g. image/jpeg).
     * @return array Array of extracted items [['name' => 'Pizza', 'price' => 12.00], ...]
     */
    public function scanMenuImage(string $base64Image, string $mimeType): array
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (empty($apiKey)) {
            throw new \Exception('Gemini API key is not configured.');
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

        $prompt = "You are a highly accurate data extraction bot. I am providing you with an image of a restaurant menu. The menu might be written in English, Bengali (Bangla), or a mix of both. Extract all the individual food/drink items and their corresponding prices. Return ONLY a valid JSON array of objects. Each object must have exactly two keys: 'name' (string, keeping the original Bengali or English text) and 'price' (number/float). Do not include currency symbols in the price. If you cannot read anything, return an empty array []. Do not include markdown formatting like ```json.";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $base64Image
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json'
            ]
        ];

        $response = Http::post($url, $payload);

        if ($response->failed()) {
            Log::error('Gemini API Error: ' . $response->body());
            throw new \Exception('Failed to process image with AI.');
        }

        $data = $response->json();
        
        // Extract the text content from the Gemini response structure
        $extractedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
        
        // Parse the JSON string into a PHP array
        $items = json_decode($extractedText, true);

        if (!is_array($items)) {
            Log::error('Gemini returned invalid JSON: ' . $extractedText);
            throw new \Exception('AI could not understand the menu format.');
        }

        return $items;
    }
}

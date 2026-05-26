<?php

namespace App\Http\Controllers;

use App\Models\BillSession;
use App\Services\MenuScannerService;
use Illuminate\Http\Request;

class MenuScannerController extends Controller
{
    public function scan(Request $request, BillSession $bill, MenuScannerService $scanner)
    {
        // Security check
        if ($bill->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'menu_image' => 'required|image|max:10240', // max 10MB
        ]);

        try {
            $file = $request->file('menu_image');
            $base64Image = base64_encode(file_get_contents($file->path()));
            $mimeType = $file->getMimeType();

            // Call AI Service
            $items = $scanner->scanMenuImage($base64Image, $mimeType);

            if (empty($items)) {
                return back()->withErrors(['menu_image' => 'The AI could not identify any menu items in this image. Please try a clearer picture.']);
            }

            // Append to the bill session's custom_menu
            $currentMenu = $bill->custom_menu ?? [];
            
            foreach ($items as $item) {
                if (isset($item['name']) && isset($item['price'])) {
                    // Ensure price is a clean float
                    $price = (float) preg_replace('/[^0-9.]/', '', (string) $item['price']);
                    
                    if ($price > 0 && !empty(trim($item['name']))) {
                        $currentMenu[] = [
                            'name' => trim($item['name']),
                            'price' => $price
                        ];
                    }
                }
            }

            $bill->update(['custom_menu' => $currentMenu]);

            return back()->with('status', 'Successfully scanned ' . count($items) . ' items from the menu!');

        } catch (\Exception $e) {
            return back()->withErrors(['menu_image' => 'Failed to process image: ' . $e->getMessage()]);
        }
    }
}

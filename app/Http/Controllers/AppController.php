<?php

namespace App\Http\Controllers;

use App\Models\Fsic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

class AppController extends Controller
{
    public function searchFSIC(Request $request)
    {
        // Retrieve the FSIC number from the request
        $fsic_no = $request->fsic_no;

        // Find the FSIC record using the fsic_no
        $fsic = Fsic::with('application', 'inspector', 'marshall')
            ->where('fsic_no', $fsic_no)
            ->first();

        if (!$fsic) {
            return response()->json(['message' => 'FSIC No not found'], 404);
        }

        // Check if the image already exists in the Spatie Media Library
        $existingMedia = $fsic->getFirstMedia('fsic_certificates');

        if ($existingMedia) {
            return response()->json([
                'fsic_no' => $fsic->fsic_no,
                'file_url' => $existingMedia->getUrl() // Return the existing image URL
            ]);
        }

        // Retrieve the application model
        $application = $fsic->application;

        // Get the QR Code by its name from the 'fsic_requirements' media collection
        $qrCodeMedia = $application->getMedia('fsic_requirements')->where('name', 'QR Code')->first();

        if ($qrCodeMedia) {
            // Get the URL of the QR code
            $fsicQrCodeUrl = $qrCodeMedia->getUrl(); // This will give you the QR code URL

            // Optionally, if you want to store it directly on the FSIC model
            $fsic->fsicQrCode = $fsicQrCodeUrl; // Store the QR code URL in the fsicQrCode attribute
        }

        // Render the Blade view into HTML (for the certificate)
        $html = view('pdf.view_certificate', compact('fsic'))->render();

        // Define the temporary save path for the certificate image
        $directory = public_path('upload');
        $filename = 'fsic_certificate_' . $fsic->fsic_no . '.png';
        $filePath = $directory . '/' . $filename;

        // Ensure the upload directory exists
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true); // Create the directory if it doesn't exist
        }

        // Generate and save the image using Browsershot
        Browsershot::html($html)
            ->windowSize(1920, 1080)
            ->save($filePath);

        // Store the generated certificate image in Spatie Media Library
        $fsic->addMedia($filePath)
            ->toMediaCollection('fsic_certificates');

        // Delete the temporary image file after storing it
        File::delete($filePath);

        // Return the URL of the generated image
        return response()->json([
            'fsic_no' => $fsic->fsic_no,
            'file_url' => $fsic->getFirstMediaUrl('fsic_certificates') // Return the generated image URL
        ]);
    }
}

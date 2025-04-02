<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\Fsic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class AppController extends Controller
{
    public function searchFSIC(Request $request)
    {
        $fsic_no = $request->fsic_no;

        $fsic = Fsic::with('application', 'inspector', 'marshall')
            ->where('fsic_no', $fsic_no)
            ->first();

        if (!$fsic) {
            return response()->json(['message' => 'FSIC No not found'], 200);
        }

        $existingMedia = $fsic->getFirstMedia('fsic_certificates');

        if ($existingMedia) {
            return response()->json([
                'fsic_no' => $fsic->fsic_no,
                'file_url' => $existingMedia->getUrl()
            ]);
        }

        $application = $fsic->application;
        $bfpLogo = url('img/bfp.webp');
        $dilgLogo = url('img/dilg.webp');
        $fsic->bfpLogo = $bfpLogo;
        $fsic->dilgLogo = $dilgLogo;

        $qrCodeMedia = $application->getMedia('fsic_requirements')->where('name', 'QR Code')->first();

        if ($qrCodeMedia) {
            $fsicQrCodeUrl = $qrCodeMedia->getUrl();
            $fsic->fsicQrCode = $fsicQrCodeUrl;
        }

        // Render the Blade view into HTML (for the certificate)
        $html = view('pdf.view_certificate', compact('fsic'))->render();

        $directory = public_path('upload');
        $filename = 'fsic_certificate_' . $fsic->fsic_no . '.html';
        $filePath = $directory . '/' . $filename;

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        file_put_contents($filePath, $html);

        $client = new Client();

        try {
            $response = $client->request('GET', 'https://api.screenshotlayer.com/api/capture', [
                'query' => [
                    'access_key' => env('SCREENSHOTLAYER_API_KEY'),
                    'url' => url('upload/' . $filename),
                    'viewport' => '816x1344',
                    'width' => 816,
                    'fullpage' => true,
                    'format' => 'png',
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $imageFilePath = public_path('upload/fsic_certificate_' . $fsic->fsic_no . '.png');

                file_put_contents($imageFilePath, $response->getBody());

                $fsic->addMedia($imageFilePath)->toMediaCollection('fsic_certificates');

                File::delete($filePath);
                File::delete($imageFilePath);

                return response()->json([
                    'fsic_no' => $fsic->fsic_no,
                    'file_url' => $fsic->getFirstMediaUrl('fsic_certificates')
                ]);
            } else {
                Log::error('Screenshotlayer API error: ', [
                    'status' => $response->getStatusCode(),
                    'body' => $response->getBody(),
                ]);
                return response()->json(['message' => 'Error generating screenshot'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Screenshotlayer API exception: ', [
                'message' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error generating screenshot'], 500);
        }
    }

    public function mapping()
    {
        $establishmentsQuery = Establishment::query()->with(['applications.fsics']);

        $establishments = $establishmentsQuery->get();

        foreach ($establishments as $establishment) {
            $application = $establishment->applications->first();
            $inspected = $application && $application->fsics->isNotEmpty();

            if ($application) {
                $response[] = [
                    floatval($establishment->location_latitude),
                    floatval($establishment->location_longitude),
                    $establishment->id,
                    $inspected,
                    $inspected ? 'Inspected' : 'Not Inspected'
                ];
            }
        }

        return view('establishment', compact('response'));
    }
}

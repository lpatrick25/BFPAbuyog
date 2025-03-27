<?php

namespace App\Http\Controllers;

use App\Models\Fsic;
use App\Models\Application;
use App\Models\Inspector;
use App\Models\Marshall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\FSICCertificateMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FsicController extends Controller
{
    /**
     * Store a new FSIC.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'issue_date' => 'required|date',
            'expiration_date' => 'required|date|after:issue_date',
            'amount' => 'required|numeric|min:0',
            'or_number' => 'required|string|max:10|unique:fsics,or_number',
            'inspector_id' => 'required|exists:inspectors,id',
            'marshall_id' => 'required|exists:marshalls,id',
        ]);

        DB::beginTransaction();
        try {
            // Create FSIC
            $fsic = Fsic::create($validated);

            DB::commit();
            Log::info('FSIC created successfully', ['fsic_id' => $fsic->id]);

            return response()->json(['message' => 'FSIC issued successfully', 'fsic' => $fsic], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error issuing FSIC', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Update an FSIC.
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'issue_date' => 'required|date',
            'expiration_date' => 'required|date|after:issue_date',
            'amount' => 'required|numeric|min:0',
            'or_number' => 'required|string|max:10|unique:fsics,or_number,' . $id,
            'inspector_id' => 'required|exists:inspectors,id',
            'marshall_id' => 'required|exists:marshalls,id',
        ]);

        DB::beginTransaction();
        try {
            // Find FSIC
            $fsic = Fsic::findOrFail($id);

            // Update FSIC
            $fsic->update($validated);

            DB::commit();
            Log::info('FSIC updated successfully', ['fsic_id' => $fsic->id]);

            return response()->json(['message' => 'FSIC updated successfully', 'fsic' => $fsic], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating FSIC', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete an FSIC.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find FSIC
            $fsic = Fsic::findOrFail($id);

            // Delete FSIC
            $fsic->delete();

            DB::commit();
            Log::info('FSIC deleted successfully', ['fsic_id' => $id]);

            return response()->json(['message' => 'FSIC deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting FSIC', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get all FSICs.
     */
    public function index()
    {
        try {
            $fsics = Fsic::with(['application', 'inspector', 'marshall'])->get();
            return response()->json(['fsics' => $fsics], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching FSICs', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single FSIC.
     */
    public function show($id)
    {
        try {
            $fsic = Fsic::with(['application', 'inspector', 'marshall'])->findOrFail($id);
            return response()->json(['fsic' => $fsic], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching FSIC', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'FSIC not found.'], 404);
        }
    }

    public function sendFsicCertificate($fsicId)
    {
        // Retrieve FSIC record and related data
        $fsic = FSIC::with('application.establishment.client')->findOrFail($fsicId);
        $application = $fsic->application;
        $establishment = $application->establishment;
        $client = $establishment->client;

        // Extract and format relevant data
        $fsicData = [
            'fsic_no' => $fsic->id,
            'issue_date' => $fsic->issue_date->format('F j, Y'),
            'expiration_date' => $fsic->expiration_date->format('F j, Y'),
            'amount' => number_format($fsic->amount, 2),
            'or_number' => $fsic->or_number,
            'payment_date' => $fsic->payment_date->format('F j, Y'),
            'filePath' => "fsic_certificates/FSIC_{$fsic->id}.pdf",
        ];

        // Extract applicant and establishment details
        $applicationData = [
            'name' => Str::title($establishment->name), // Proper case formatting
            'client_name' => "{$client->last_name}, {$client->first_name}",
            'address_brgy' => Str::title($application->address_brgy),
            'inspector_name' => Str::title($fsic->inspector_name),
            'marshall_name' => Str::title($fsic->marshall_name),
            'fsic_type' => $fsic->type, // Add FSIC Type for dynamic checkbox selection
        ];

        // Generate FSIC Certificate as PDF
        $pdf = Pdf::loadView('pdf.fsic_certificate', compact('fsicData', 'applicationData'));

        // Store the generated PDF in storage
        Storage::put($fsicData['filePath'], $pdf->output());

        // Send Email with PDF Attachment
        Mail::to($client->email)->send(new FSICCertificateMail($applicationData, $fsicData));

        return response()->json(['message' => 'FSIC Certificate sent successfully']);
    }
}

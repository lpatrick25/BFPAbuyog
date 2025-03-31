<?php

namespace App\Observers;

use App\Mail\FsicNotification;
use App\Models\ApplicationStatus;
use App\Models\Fsic;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class FsicObserver
{
    public function created(Fsic $fsic): void
    {
        $encryptedFsicNo = Crypt::encryptString($fsic->fsic_no);

        $baseUrl = url('/fsic_no');
        $qrData = $baseUrl . '/' . $encryptedFsicNo;

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $qrData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 20,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: public_path('img/bfp.webp'),
            logoResizeToWidth: 70,
            logoPunchoutBackground: true,
            labelText: $fsic->application->application_number,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        $qrCodeResult = $builder->build();

        $qrCodePath = storage_path('app/public/temp_qr.png');
        file_put_contents($qrCodePath, $qrCodeResult->getString());

        $fsic->application->addMedia($qrCodePath)
            ->preservingOriginal()
            ->usingName('QR Code')
            ->toMediaCollection('fsic_requirements');

        $fsic->fsicQrCode = $qrCodePath;

        $pdf = Pdf::loadView('pdf.fsic_certificate', compact('fsic'));
        $pdf->setPaper('Legal');
        $pdfFileName = $fsic->fsic_no . '.pdf';
        $pdfFilePath = storage_path('app/public/' . $pdfFileName);

        $pdf->save($pdfFilePath);

        $fsic->application->addMedia($pdfFilePath)
            ->preservingOriginal()
            ->usingName('FSIC Certificate')
            ->toMediaCollection('fsic_requirements');

        $application = $fsic->application;
        $establishment = $application->establishment ?? null;
        $client = $establishment->client ?? null;

        ApplicationStatus::create([
            'application_id' => $application->id,
            'status' => 'Certificate Issued',
        ]);

        if ($client && $client->email) {
            Mail::to($client->email)->send(new FsicNotification($fsic, $pdfFilePath));
        }

        unlink($qrCodePath);
        unlink($pdfFilePath);
    }
}

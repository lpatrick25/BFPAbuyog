<?php

namespace App\Mail;

use App\Models\ApplicationStatus;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $applicationStatus;

    public function __construct(ApplicationStatus $applicationStatus)
    {
        $this->applicationStatus = $applicationStatus;
    }

    public function build()
    {
        // Define subject and message content dynamically
        $inspectionType = 'Reinspection';
        $remarks = $this->applicationStatus->remarks;
        $subject = 'Application Status Update';
        $remarksMessage = 'Please review the details below.';

        switch ($remarks) {
            case 'Issue Notice to Comply':
                $subject = 'Notice to Comply Issued';
                $remarksMessage = 'You are required to comply with the stated fire safety requirements.';
                break;
            case 'Issue Notice to Correct Violation':
                $subject = 'Notice to Correct Violation Issued';
                $remarksMessage = 'You are required to correct the stated violations promptly.';
                break;
            case 'Issue Abatement Order':
                $subject = 'Abatement Order Issued';
                $remarksMessage = 'An abatement order has been issued. Please take immediate action.';
                break;
            case 'Issue Stoppage of Operation':
                $subject = 'Stoppage of Operation Issued';
                $remarksMessage = 'A stoppage of operation has been enforced. Please resolve the issues immediately.';
                break;
            case 'Establishment Complied':
                $inspectionType = 'Completed';
                $subject = 'Establishment Complied';
                $remarksMessage = 'Inspection completed: The establishment is now fully compliant.';
                break;
            default:
                $inspectionType = 'Pending';
                $subject = 'Application Denied';
                $remarksMessage = 'Your application has been denied. Please address the issues mentioned.';
                break;
        }

        $schedule = Schedule::where('application_id', $this->applicationStatus->application->id)->first();

        return $this->subject($subject)
            ->view('emails.application_status_updated')
            ->with([
                'status' => $this->applicationStatus->status,
                'remarks' => $remarks ?? 'No remarks provided.',
                'client' => $this->applicationStatus->application->establishment->client,
                'establishment' => $this->applicationStatus->application->establishment,
                'subject' => $subject,
                'remarksMessage' => $remarksMessage,
                'scheduleDate' => $schedule?->schedule_date ?? '',
                'inspector' => $schedule?->inspector,
                'inspectionType' => $inspectionType,
            ]);
    }
}

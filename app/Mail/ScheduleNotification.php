<?php

namespace App\Mail;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;
    public $scheduleType;

    /**
     * Create a new message instance.
     */
    public function __construct(Schedule $schedule, $scheduleType)
    {
        $this->schedule = $schedule;
        $this->scheduleType = $scheduleType;
    }

    /**
     * Build the message.
     */
    public function build()
    {

        $subject = '';
        if ($this->scheduleType == 'Reinspection') {
            $subject = 'Establishment Scheduled for Reinspection';
        } else if ($this->scheduleType == 'Reschedule') {
            $subject = 'Establishment Inspection Reschedule';
        } else {
            $subject = 'Establishment Scheduled for Inspection';
        }

        return $this->subject($subject)
            ->markdown('emails.schedule_notification')
            ->with([
                'scheduleDate' => $this->schedule->schedule_date,
                'inspector' => $this->schedule->inspector,
                'client' => $this->schedule->application->establishment->client,
                'establishment' => $this->schedule->application->establishment,
                'scheduleType' => $this->scheduleType,
                'subject' => $subject,
            ]);
    }
}

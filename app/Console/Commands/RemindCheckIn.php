<?php

namespace App\Console\Commands;

use App\Mail\CheckInReminderMail;
use App\Models\PeminjamanTransaksi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RemindCheckIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peminjaman:remind-checkin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic email reminder 24-48 hours before reservation check-in for approved bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $minTime = $now->copy()->addHours(24);
        $maxTime = $now->copy()->addHours(48);

        $this->info("Checking reservations starting between {$minTime} and {$maxTime}...");

        $peminjamans = PeminjamanTransaksi::where('statusApproval', 'APPROVED')
            ->where('statusPeminjaman', 'RESERVASI')
            ->where('reminderSent', false)
            ->whereBetween('jamMulai', [$minTime, $maxTime])
            ->get();

        $count = 0;
        foreach ($peminjamans as $peminjaman) {
            $user = $peminjaman->user ?? User::where('guestId', $peminjaman->guestId)->first();
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new CheckInReminderMail($peminjaman));
                    $peminjaman->update(['reminderSent' => true]);
                    $this->info("Reminder sent to {$user->email} for booking {$peminjaman->kodePeminjaman}");
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to send reminder for booking {$peminjaman->kodePeminjaman}: " . $e->getMessage());
                }
            }
        }

        $this->info("Finished sending {$count} reminders.");
    }
}

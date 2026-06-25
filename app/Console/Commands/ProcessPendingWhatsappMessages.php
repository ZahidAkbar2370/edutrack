<?php

namespace App\Console\Commands;

use App\Services\WhatsappMessageService;
use Illuminate\Console\Command;

class ProcessPendingWhatsappMessages extends Command
{
    protected $signature = 'whatsapp:process-pending {--loop : Run continuously every 3 seconds}';

    protected $description = 'Send pending WhatsApp messages and update their status';

    public function handle(WhatsappMessageService $whatsappMessageService): int
    {
        if ($this->option('loop')) {
            $this->info('Processing pending WhatsApp messages every 3 seconds. Press Ctrl+C to stop.');

            while (true) {
                $this->runOnce($whatsappMessageService);
                sleep(3);
            }
        }

        $this->runOnce($whatsappMessageService);

        return self::SUCCESS;
    }

    protected function runOnce(WhatsappMessageService $whatsappMessageService): void
    {
        $result = $whatsappMessageService->processPendingMessages();

        if ($result['processed'] === 0) {
            return;
        }

        $this->line(sprintf(
            'Processed: %d | Sent: %d | Failed: %d',
            $result['processed'],
            $result['sent'],
            $result['failed']
        ));
    }
}

<?php

namespace App\Services;

use App\Models\WhatsappDevice;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappMessageService
{
    public function processPendingMessages(int $limit = 20): array
    {
        $processed = 0;
        $sent = 0;
        $failed = 0;

        $messages = WhatsappMessage::query()
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->limit($limit)
            ->get();

        foreach ($messages as $message) {
            $processed++;

            if ($this->sendMessage($message)) {
                $message->update(['status' => 'sent']);
                $sent++;
                continue;
            }

            $message->update(['status' => 'failed']);
            $failed++;
        }

        return compact('processed', 'sent', 'failed');
    }

    public function sendMessage(WhatsappMessage $message): bool
    {
        $device = WhatsappDevice::query()
            ->where('school_id', $message->school_id)
            ->first();

        if (empty($device) || empty($device->wachat_device_number)) {
            Log::warning('WhatsApp device not found for school.', [
                'school_id' => $message->school_id,
                'message_id' => $message->id,
            ]);

            return false;
        }

        $sender = $message->from_number ?: $device->wachat_device_number;

        if (empty($message->to_number) || empty($message->message)) {
            Log::warning('WhatsApp message missing recipient or body.', [
                'message_id' => $message->id,
            ]);

            return false;
        }

        try {
            $response = Http::timeout(30)->post(env('WACHAT_API_URL').'/send-message', [
                'api_key' => env('WACHAT_API_KEY'),
                'sender' => $sender,
                'number' => $message->to_number,
                'message' => $message->message,
            ]);

            if (!$response->successful()) {
                Log::warning('WhatsApp API request failed.', [
                    'message_id' => $message->id,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return false;
            }

            $body = $response->json() ?? [];

            if (($body['status'] ?? null) === 'success') {
                return true;
            }

            if (str_contains(strtolower((string) ($body['msg'] ?? '')), 'success')) {
                return true;
            }

            Log::warning('WhatsApp API returned unsuccessful response.', [
                'message_id' => $message->id,
                'body' => $body,
            ]);

            return false;
        } catch (\Throwable $exception) {
            Log::error('WhatsApp send exception.', [
                'message_id' => $message->id,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}

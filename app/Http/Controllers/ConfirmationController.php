<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Psr\Http\Client\ClientExceptionInterface;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Exception\Exception;

class ConfirmationController extends Controller
{
    public function TelegramConfirm(Request $request) {
        $token = '6996473729:AAHh3jdhVE3LCOC3UkL8o466ZHWAgtsmuAc';
        $confirmationCode = Str::random(6);
        $chat_id = $request->telegramId;

        $response = Http::post("https://api.telegram.org/bot$token/sendMessage", [
            'chat_id' => $chat_id,
            'text' => "Ваш код подтверждения: {$confirmationCode}"
        ]);

        if ($response->ok()) {
            return response()->json(['success' => true, 'confirmation_code' => $confirmationCode]);
        } else {
            return response()->json(['success' => false, 'error' => $response->json()]);
        }
    }

    /**
     * @throws Exception
     * @throws ClientExceptionInterface
     */
    public function SMSConfirm(Request $request) {
        $confirmationCode = Str::random(6);
        $numberPhone = $request->phoneNumber;
        $basic  = new Basic("3ee48fb5", "wbozzUmeYWL7PASz");
        $client = new Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($numberPhone, 'EasyCode', 'Code Confirmation:'. $confirmationCode)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            echo "The message was sent successfully\n";
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }
    }
    public function EmailConfirm(Request $request) {
        $email = Auth::user()->email;
        $confirmationCode = Str::random(6);

        // Отправка электронного письма с кодом подтверждения
        Mail::raw("Ваш код подтверждения: {$confirmationCode}", function ($message) use ($email) {
            $message->to($email)->subject('Код подтверждения');
        });

        // Возвращаем ответ с кодом подтверждения
        return response()->json(['success' => true, 'confirmation_code' => $confirmationCode]);
    }
}

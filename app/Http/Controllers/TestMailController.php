<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\TestMailRequest;
use App\Notifications\TestNotification;
use Notification;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestMailController extends Controller
{
    /**
     * Send test email
     *
     * @return JsonResponse
     */
    public function sendTestMail(TestMailRequest $request): JsonResponse
    {
        Notification::route('mail', $request->email)->notify(new TestNotification);

        return response()->json('Testmail erfolgreicht gesendet');
    }
}

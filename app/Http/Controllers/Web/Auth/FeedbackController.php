<?php

namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;
use App\Mail\ContactWithAdminMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

final class FeedbackController extends Controller
{
    public function contactWithAdmin(Request $request): JsonResponse
    {
        $request->validate(['login' => 'required|string']);

        $adminMail = 'zarkowskiy@gmail.com';
        $login = $request->get('login');

        Mail::to($adminMail)->send(new ContactWithAdminMail($login));

        return new JsonResponse([], 201);
    }
}

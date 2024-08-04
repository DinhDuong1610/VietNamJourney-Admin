<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\User;
use App\Http\Services\EmailService;
use Exception;

class EmailController extends Controller
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    public function getEmailsUser($userId, Request $request)
    {
        try {
            $emails = $this->emailService->getEmailsUser($userId);
            $emailsSend = $this->emailService->getEmailsUserSend($userId);
            $unreadEmailsCount = $this->emailService->getEmailsUser($userId, 0);
            return response()->json([
                'emails' => $emails->items(),
                'totalPages' => $emails->lastPage(),
                'totalEmails' => $emails->total(),
                'emailsSend' => $emailsSend->items(),
                'totalPagesSend' => $emailsSend->lastPage(),
                'totalEmailsSend' => $emailsSend->total(),
                'unreadEmailsCount' => $unreadEmailsCount->total()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching user emails',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getEmailById($userId, $id)
    {
        try {
            $email = $this->emailService->getEmailById($id);

            if (!$email) {
                return response()->json([
                    'message' => 'Email not found',
                ], 404);
            }

            $emails = $this->emailService->getEmailsUser($userId);
            $emailsSend = $this->emailService->getEmailsUserSend($userId);
            $unreadEmailsCount = $this->emailService->getEmailsUser($userId, 0);

            return response()->json([
                'email' => $email,
                'totalEmails' => $emails->total(),
                'totalEmailsSend' => $emailsSend->total(),
                'unreadEmailsCount' => $unreadEmailsCount->total(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching email',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateEmailStatus(Request $request, $id)
    {
        try {
            $this->emailService->updateEmailStatus($id);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createEmail(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'isAdmin' => 'required|integer|in:0,1',
            'userId' => 'required|integer',
        ]);

        $data = $request->only(['title', 'content', 'isAdmin', 'userId']);

        try {
            $this->emailService->createEmail($data);
            return response()->json([
                'message' => 'Email created successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating email',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

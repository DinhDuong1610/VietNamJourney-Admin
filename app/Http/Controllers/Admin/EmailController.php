<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\User;
use App\Http\Services\EmailService;
use Exception;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function getEmailsAdmin()
    {
        $emails = $this->emailService->getEmailsAdmin();

        return view('admin.pages.email.index', [
            'emails' => $emails,
            'emails_send' => $this->emailService->getEmailsSend(0),
            'emails_admin' => $this->emailService->getEmailsAdmin(0)
        ]);
    }

    public function getEmailsSend()
    {
        $emails = $this->emailService->getEmailsSend();

        return view('admin.pages.email.index', [
            'emails' => $emails,
            'emails_send' => $this->emailService->getEmailsSend(0),
            'emails_admin' => $this->emailService->getEmailsAdmin(0)
        ]);
    }

    public function compose()
    {
        $emails = $this->emailService->getEmailsAdmin();

        return view('admin.pages.email.compose', [
            'emails_send' => $this->emailService->getEmailsSend(0),
            'emails_admin' => $this->emailService->getEmailsAdmin(0)
        ]);
    }

    public function getEmailById($id)
    {
        $emails = $this->emailService->getEmailsAdmin();
        $email = $this->emailService->getEmailById($id);

        if (!$email) {
            abort(404, 'Email not found');
        }

        return view('admin.pages.email.read', [
            'email' => $email,
            'emails_send' => $this->emailService->getEmailsSend(0),
            'emails_admin' => $this->emailService->getEmailsAdmin(0)
        ]);
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
            'username' => 'required',
        ]);

        $data = $request->only(['title', 'content', 'isAdmin', 'username']);

        // Get the userId from Username
        $user = User::where('Username', $data['username'])->first();

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'User not found']);
        }

        // Add userId to data
        $data['userId'] = $user->id;

        // Remove username from data
        unset($data['username']);

        try {
            $email = $this->emailService->createEmail($data);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        return redirect()->route('admin.pages.email.send')->with('success', 'Email created successfully!');
    }
}

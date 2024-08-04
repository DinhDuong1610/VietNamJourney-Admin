<?php

namespace App\Http\Services;

use App\Models\Email;
use App\Models\User;

class EmailService extends BaseCrudService
{
    private Email $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
        parent::__construct($email);
    }

    public function getEmailsAdmin($status = null)
    {
        $query = $this->email
            ->with('user')
            ->where('isAdmin', 0)
            ->orderBy('created_at', 'desc');

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->paginate(10);
    }

    public function getEmailsSend($status = null)
    {
        $query = $this->email
            ->with('user')
            ->where('isAdmin', 1)
            ->orderBy('created_at', 'desc');

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->paginate(10);
    }

    public function getEmailById(int $id)
    {
        return $this->email->with('user')->find($id);
    }

    public function createEmail(array $data)
    {
        return $this->email->create($data);
    }

    public function getEmailsUser($userId, $status = null)
    {
        $query = $this->email
            ->with('user')
            ->where('userId', $userId)
            ->where('isAdmin', 1)
            ->orderBy('created_at', 'desc');

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->paginate(10);
    }

    public function getEmailsUserSend($userId)
    {
        return $this->email
            ->with('user')
            ->where('userId', $userId)
            ->where('isAdmin', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function updateEmailStatus(int $id, int $status = 1)
    {
        $email = $this->email->find($id);

        if (!$email) {
            throw new \Exception('Email not found');
        }

        $email->status = $status;
        $email->save();

        return $email;
    }
}

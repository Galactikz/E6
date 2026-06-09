<?php

namespace App\Contracts;

interface NotificationProviderInterface
{
    public function sendSms(string $to, string $message): bool;
    public function sendEmail(string $to, string $subject, string $body): bool;
}

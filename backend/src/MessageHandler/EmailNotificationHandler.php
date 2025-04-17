<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\EmailNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailNotificationHandler implements MessageHandlerInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(EmailNotification $message)
    {
        $email = (new TemplatedEmail())
            ->from($message->getFrom())
            ->to($message->getTo())
            ->subject($message->getSubject())
            ->htmlTemplate($message->getTemplate())
            ->context($message->getContext());

        $this->mailer->send($email);
    }
}

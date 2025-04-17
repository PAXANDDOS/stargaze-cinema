<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Mime\Address;

class EmailNotification
{
    public function __construct(
        private array $to,
        private string $subject,
        private string $template,
        private array $context,
        private array $from = ['support@stargaze.com', 'Stargaze Cinema'],
    ) {
    }

    /**
     * Get the value of from.
     */
    public function getFrom(): Address
    {
        return new Address($this->from[0], $this->from[1]);
    }

    /**
     * Get the value of to.
     */
    public function getTo(): Address
    {
        return new Address($this->to[0], $this->to[1]);
    }

    /**
     * Get the value of subject.
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Get the value of template.
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Get the value of context.
     */
    public function getContext(): array
    {
        return $this->context;
    }
}

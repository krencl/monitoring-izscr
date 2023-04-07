<?php

namespace App\Service;

use App\DTO\Email;
use DateTime;
use DateTimeInterface;
use PhpImap\Exceptions\ConnectionException;
use PhpImap\Mailbox;

final class ImapChecker
{
	private const CHECK_DOMAIN = 'grh.izscr.cz';
	private const CHECK_PRINTABLE = 'quoted-printable';
	private Mailbox $mailbox;

	public function __construct(private readonly Config $config)
	{
		$this->mailbox = new Mailbox(
			sprintf('{%s:%d/imap/ssl}INBOX', $this->config->get('email.host'), $this->config->get('email.port')),
			$this->config->get('email.user'),
			$this->config->get('email.pass'),
		);
		$this->mailbox->setConnectionArgs(OP_READONLY | OP_DEBUG);
	}

	public function connect(): bool
	{
		try {
			$this->mailbox->checkMailbox();
		} catch (ConnectionException $e) {
			return false;
		}

		return true;
	}

	private function disconnect(): void
	{
		$this->mailbox->disconnect();
	}

	/** @return array<Email> */
	public function getEmails(DateTimeInterface $since): array
	{
		$search = $this->mailbox->searchMailbox(sprintf('SINCE "%s"', $since->format('Y-m-d')));
		if (empty($search)) {
			return [];
		}

		$emails = [];
		foreach ($search as $id) {
			$emails[] = $this->fetchEmail($id);
		}

		return array_filter($emails);
	}

	private function fetchEmail(int $id): Email|null
	{
		$mail = $this->mailbox->getMail($id, false);

		if (!str_contains($mail->headersRaw, self::CHECK_DOMAIN)) {
			return null;
		}

		return new Email(
			$mail->messageId ?: $mail->subject . ' ' . $mail->date,
			DateTime::createFromFormat(DateTimeInterface::W3C, $mail->date),
			$mail->subject,
			$mail->headersRaw,
			$mail->textPlain,
			$mail->textHtml,
		);
	}

	public function __destruct()
	{
		$this->disconnect();
	}
}

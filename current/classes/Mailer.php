<?php
/**
 * @author    METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2018, METANET AG
 */

namespace classes;

abstract class Mailer
{
	public $smtp;
	public $smtp_host;
	public $smtp_port;
	public $smtp_username;
	public $smtp_password;
	public $smtp_debug;
	public $smtp_secure;
	protected $fromEmail;
	protected $fromName;
	protected $replyToEmail;
	protected $replyToName;
	protected $charset = 'UTF-8';
	protected $encoding = 'quoted-printable';
	protected $priority = 3;
	protected $confirmation = 0;
	protected $customHeaders = [];
	/** @var  PHPMailer $mailer */
	protected $mailer;

	public function create($fromEmail = null, $fromName = null, $charset = null, $encoding = null, $priority = null, $confirmation = null)
	{

		$this->setFromEmail($fromEmail);
		$this->setFromName($fromName);
		$this->setCharset($charset);
		$this->setEncoding($encoding);
		$this->setPriority($priority);
		$this->setConfirmation($confirmation);
	}

	private function loadMailer()
	{
		$this->setReplyToEmail(null);
		$this->setReplyToName(null);

		$this->mailer = new PHPMailer(true);
		$this->mailer->CharSet = $this->charset;
		$this->mailer->Encoding = $this->encoding;
		$this->mailer->Priority = $this->priority;

		if ($this->smtp === true) {
			$this->mailer->Mailer = 'smtp';
			$this->mailer->Host = $this->smtp_host;
			$this->mailer->Port = $this->smtp_port;
			$this->mailer->Username = $this->smtp_username;
			$this->mailer->Password = $this->smtp_password;
			$this->mailer->SMTPAuth = true;
			$this->mailer->SMTPDebug = $this->smtp_debug;
			$this->mailer->SMTPSecure = $this->smtp_secure;
		}
		$this->mailer->From = $this->fromEmail;
		$this->mailer->FromName = $this->fromName;
		$this->mailer->addReplyTo($this->replyToEmail, $this->replyToName);
		$this->mailer->Sender = $this->fromEmail;

		if ($this->confirmation === true) {
			$this->mailer->ConfirmReadingTo = $this->fromEmail;
		}

		if (count($this->customHeaders) != 0) {
			foreach ($this->customHeaders AS $customHeader) {
				$this->mailer->addCustomHeader($customHeader);
			}
		}
	}

	public function setFromEmail($fromEmail)
	{
		if ($fromEmail !== null && $fromEmail !== '') {
			$this->fromEmail = $fromEmail;
		}
	}

	public function setFromName($fromName)
	{
		if ($fromName !== null && $fromName !== '') {
			$this->fromName = $fromName;
		}
		if ($this->fromName === null || $this->fromName === '') {
			$this->fromName = $this->fromEmail;
		}
	}

	public function setReplyToEmail($replyToEmail)
	{
		if ($replyToEmail !== null && $replyToEmail !== '') {
			$this->replyToEmail = $replyToEmail;
		}
		if ($this->replyToEmail === null || $this->replyToEmail === '') {
			$this->replyToEmail = $this->fromEmail;
		}
	}

	public function setReplyToName($replyToName)
	{
		if ($replyToName !== null && $replyToName !== '') {
			$this->replyToName = $replyToName;
		}
		if ($this->replyToName === null || $this->replyToName === '') {
			$this->replyToName = $this->fromName;
		}
	}

	public function setCharset($charset)
	{
		if ($charset !== null && $charset !== '') {
			$this->charset = $charset;
		}
	}

	public function setEncoding($encoding)
	{
		if ($encoding !== null && $encoding !== '') {
			$this->encoding = $encoding;
		}
	}

	public function setPriority($priority)
	{
		if ($priority !== null && $priority !== '') {
			$this->priority = $priority;
		}
	}

	public function setConfirmation($confirmation)
	{
		if ($confirmation !== null && $confirmation !== '') {
			$this->confirmation = $confirmation;
		}
	}

	public function addCustomHeader($header)
	{
		$this->customHeaders[] = $header;
	}

	protected function sendMail($toEmail, $toName, $subject, $body, $format = 'text', $altBody = '', $attArr = [], $cc = [], $bcc = [])
	{
		$this->loadMailer();
		$this->mailer->Subject = $subject;
		$this->mailer->Body = $body;

		if ($format == 'html') {
			$this->mailer->isHTML();
			if ($altBody != '') {
				$this->mailer->AltBody = $altBody;
			}
		}

		if (count($attArr) != 0) {
			foreach ($attArr AS $attachment) {
				if (is_string($attachment)) {
					$this->mailer->addAttachment($attachment);
				} else if (isset($attachment['name']) && $attachment['name'] != '' && isset($attachment['path']) && file_exists($attachment['path'])) {
					$this->mailer->addAttachment($attachment['path'], $attachment['name']);
				}
			}
		}
		$this->mailer->addAddress($toEmail, $toName);

		foreach ($cc AS $key => $val) {
			$this->mailer->addCC($key, $val);
		}

		foreach ($bcc AS $key => $val) {
			$this->mailer->addBCC($key, $val);
		}
		$result = $this->mailer->send();

		$this->ClearAll();

		return $result;
	}

	public function ClearAll()
	{
		if (!is_null($this->mailer)) {
			$this->mailer->clearAddresses();
			$this->mailer->clearCCs();
			$this->mailer->clearBCCs();
			$this->mailer->clearReplyTos();
			$this->mailer->clearAllRecipients();
			$this->mailer->clearAttachments();
			$this->mailer->clearCustomHeaders();
			$this->mailer->isHTML(false);
			$this->mailer->Subject = '';
			$this->mailer->Body = '';
			$this->mailer->AltBody = '';
		}
	}

	public function __destruct()
	{
		$this->ClearAll();
	}
}
/* EOF */
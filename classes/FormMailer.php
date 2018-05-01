<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version

namespace classes;

class FormMailer extends Mailer
{
	public function send($to, $toName, $from, $fromName, $subject, $text, $txthtml = 'text')
	{
		$format = ($txthtml == 'html') ? 'html' : 'text';

		$this->create($from, $fromName);
		return $this->sendMail($to, $toName, $subject, $text, $format);
	}
}

/* EOF */
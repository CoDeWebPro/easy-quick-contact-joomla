<?php

/**
 * @package     JoomBoost.Site
 * @subpackage  mod_easyquickcontact
 */

namespace JoomBoost\Module\EasyQuickContact\Site\Helper;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Mail\MailerFactoryInterface;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Helper for mod_easyquickcontact.
 */
class EasyQuickContactHelper
{
	/**
	 * Register CSS/JS and module colour overrides.
	 */
	public function loadAssets(Registry $params, CMSApplicationInterface $app, int $moduleId): void
	{
		$wa     = $app->getDocument()->getWebAssetManager();
		$modUri = 'modules/mod_easyquickcontact';

		$wa->registerAndUseStyle('mod_easyquickcontact.style', $modUri . '/css/style.css')
			->registerAndUseStyle('mod_easyquickcontact.modal', $modUri . '/css/modal.css')
			->registerAndUseScript(
				'mod_easyquickcontact.main',
				$modUri . '/js/main.js',
				[],
				['defer' => true]
			);

		$buttonBg   = $this->sanitizeColor($params->get('buttonBg', '#E60000'));
		$buttonText = $this->sanitizeColor($params->get('buttonText', '#ffffff'));
		$buttonBgH  = $this->sanitizeColor($params->get('buttonBgH', '#333333'));
		$inputBg    = $this->sanitizeColor($params->get('input_bg', '#F5F5F5'));
		$inputBrd   = $this->sanitizeColor($params->get('input_brd', '#dddddd'));
		$inputText  = $this->sanitizeColor($params->get('input_text', '#333333'));

		$wa->addInlineStyle(
			'#mod_eqc_' . $moduleId . ' .je_contact button[type="submit"],'
			. ' #mod_eqc_' . $moduleId . ' .qcbutton a.je_button{'
			. 'background:' . $buttonBg . ';color:' . $buttonText . ';}'
			. '#mod_eqc_' . $moduleId . ' .je_contact button[type="submit"]:hover,'
			. ' #mod_eqc_' . $moduleId . ' .qcbutton a.je_button:hover{background:' . $buttonBgH . ';}'
			. '#mod_eqc_' . $moduleId . ' .je_contact input,'
			. ' #mod_eqc_' . $moduleId . ' .je_contact textarea{'
			. 'background-color:' . $inputBg . ';border:1px solid ' . $inputBrd . ';color:' . $inputText . ';}'
		);
	}

	/**
	 * Labels, captcha numbers, flags for the layout.
	 *
	 * @return  array<string, mixed>
	 */
	public function getDisplayData(Registry $params, CMSApplicationInterface $app, int $moduleId): array
	{
		$captchaEnabled = (int) $params->get('captcha_label', 1) === 1;
		$captchaN1      = 0;
		$captchaN2      = 0;

		if ($captchaEnabled) {
			$session   = $app->getSession();
			$captchaN1 = random_int(1, 15);
			$captchaN2 = random_int(1, 15);
			$session->set('eqc.expect.' . $moduleId, $captchaN1 + $captchaN2);
		}

		return [
			'moduleId'        => $moduleId,
			'popUp'           => (int) $params->get('popUp', 0) === 1,
			'popUpButton'     => (string) $params->get('popUpButton', 'Quick Contact'),
			'intro'           => (string) $params->get('intro', 'Intro'),
			'labelName'       => (string) $params->get('name', 'Name'),
			'labelEmail'      => (string) $params->get('email', 'Email'),
			'labelPhone'      => (string) $params->get('phone', 'Phone'),
			'labelMessage'    => (string) $params->get('message', 'Message'),
			'labelCaptcha'    => (string) $params->get('captcha', 'Captcha'),
			'labelSubmit'     => (string) $params->get('submit', 'Send'),
			'captchaEnabled'  => $captchaEnabled,
			'captchaN1'       => $captchaN1,
			'captchaN2'       => $captchaN2,
			'recipient'       => trim((string) $params->get('recipient', '')),
			'formAction'      => Uri::getInstance()->toString(),
		];
	}

	/**
	 * Handle POST for this module instance.
	 *
	 * @return  array<string, mixed>
	 */
	public function processSubmission(Registry $params, CMSApplicationInterface $app, int $moduleId): array
	{
		$result = [
			'emailSent'   => false,
			'mailError'   => false,
			'errors'      => [],
			'postedName'  => '',
			'postedEmail' => '',
			'postedPhone' => '',
			'postedMessage' => '',
		];

		$input = $app->getInput();

		if (strtoupper($input->getMethod()) !== 'POST' || !$input->get('eqc_submitted')) {
			return $result;
		}

		if ((int) $input->getInt('eqc_module_id') !== $moduleId) {
			return $result;
		}

		if (!Session::checkToken()) {
			$result['errors']['token'] = Text::_('JINVALID_TOKEN');

			return $result;
		}

		$recipient = trim((string) $params->get('recipient', ''));

		if ($recipient === '') {
			$result['errors']['recipient'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_NO_RECIPIENT');

			return $result;
		}

		$name    = trim((string) $input->getString('je_name', ''));
		$email   = trim((string) $input->getString('je_email', ''));
		$phone   = trim((string) $input->getString('je_phone', ''));
		$message = trim((string) $input->get('je_message', '', 'RAW'));

		$result['postedName']    = $name;
		$result['postedEmail']   = $email;
		$result['postedPhone']   = $phone;
		$result['postedMessage'] = $message;

		if ($name === '') {
			$result['errors']['name'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_NAME');
		}

		if ($email === '') {
			$result['errors']['email'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_EMAIL');
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$result['errors']['email'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_EMAIL_INVALID');
		}

		$phoneDigits = preg_replace('/\D+/', '', $phone) ?? '';

		if ($phoneDigits !== '' && $phoneDigits[0] === '8') {
			$phoneDigits = '7' . substr($phoneDigits, 1);
		}

		if ($phone === '' || $phone === '+7' || $phoneDigits === '' || $phoneDigits === '7') {
			$result['errors']['phone'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_PHONE');
		} elseif (!preg_match('/^7\d{10}$/', $phoneDigits)) {
			$result['errors']['phone'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_PHONE_INVALID');
		} else {
			$phone = sprintf(
				'+7 (%s) %s-%s-%s',
				substr($phoneDigits, 1, 3),
				substr($phoneDigits, 4, 3),
				substr($phoneDigits, 7, 2),
				substr($phoneDigits, 9, 2)
			);
			$result['postedPhone'] = $phone;
		}

		if ($message === '') {
			$result['errors']['message'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_MESSAGE');
		}

		$captchaEnabled = (int) $params->get('captcha_label', 1) === 1;

		if ($captchaEnabled) {
			$session = $app->getSession();
			$expect  = (int) $session->get('eqc.expect.' . $moduleId, -1);
			$answer  = (int) $input->getInt('je_captcha', -999);

			if ($expect < 0 || $answer !== $expect) {
				$result['errors']['captcha'] = Text::_('MOD_EASYQUICKCONTACT_ERROR_CAPTCHA');
			} else {
				$session->remove('eqc.expect.' . $moduleId);
			}
		}

		if ($result['errors'] !== []) {
			return $result;
		}

		$subject = (string) $params->get('subject', 'Easy Quick Contact');
		$body    = "Subject: {$subject}\n"
			. "Name: {$name}\n"
			. "Phone: {$phone}\n"
			. "Email: {$email}\n\n"
			. $message . "\n";

		try {
			$config = $app->getConfig();
			$mailer = Factory::getContainer()->get(MailerFactoryInterface::class)->createMailer();
			$mailer->setSender([
				(string) $config->get('mailfrom'),
				(string) $config->get('fromname'),
			]);
			$mailer->addReplyTo($email, $name);
			$mailer->addRecipient($recipient);
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->isHtml(false);

			if ($mailer->Send()) {
				$result['emailSent']     = true;
				$result['postedName']    = '';
				$result['postedEmail']   = '';
				$result['postedPhone']   = '';
				$result['postedMessage'] = '';
			} else {
				$result['mailError'] = true;
			}
		} catch (\Throwable $e) {
			$result['mailError'] = true;
			Log::add('mod_easyquickcontact mail failed: ' . $e->getMessage(), Log::WARNING, 'mod_easyquickcontact');
		}

		return $result;
	}

	private function sanitizeColor(string $color): string
	{
		$color = trim($color);

		if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $color)) {
			return $color;
		}

		if (preg_match('/^rgba?\([\d\s.,%]+\)$/', $color)) {
			return $color;
		}

		return '#333333';
	}
}

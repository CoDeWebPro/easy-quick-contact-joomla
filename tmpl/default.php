<?php

/**
 * @package     JoomBoost.Site
 * @subpackage  mod_easyquickcontact
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var \Joomla\Registry\Registry $params */
/** @var object $module */
/** @var int $moduleId */
/** @var bool $popUp */
/** @var string $popUpButton */
/** @var string $intro */
/** @var string $labelName */
/** @var string $labelEmail */
/** @var string $labelPhone */
/** @var string $labelMessage */
/** @var string $labelCaptcha */
/** @var string $labelSubmit */
/** @var bool $captchaEnabled */
/** @var int $captchaN1 */
/** @var int $captchaN2 */
/** @var string $recipient */
/** @var string $formAction */
/** @var bool $emailSent */
/** @var bool $mailError */
/** @var array $errors */
/** @var string $postedName */
/** @var string $postedEmail */
/** @var string $postedPhone */
/** @var string $postedMessage */

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_QUOTES, 'UTF-8');
$formId          = 'contact-je-' . $moduleId;
$wrapId          = 'mod_eqc_' . $moduleId;
$contactId       = 'je_contact_' . $moduleId;
?>
<div id="<?php echo $wrapId; ?>" class="mod-easyquickcontact<?php echo $moduleclass_sfx ? ' ' . $moduleclass_sfx : ''; ?>"<?php echo $popUp && (!empty($emailSent) || $errors !== [] || !empty($mailError)) ? ' data-eqc-force-modal="1"' : ''; ?>>
<?php if ($popUp) : ?>
	<div class="qcbutton">
		<ul>
			<li>
				<a class="cd-signup je_button" href="#<?php echo $wrapId; ?>-modal"><?php echo htmlspecialchars($popUpButton, ENT_QUOTES, 'UTF-8'); ?></a>
			</li>
		</ul>
	</div>
	<div class="cd-user-modal" id="<?php echo $wrapId; ?>-modal" data-eqc-modal>
		<div class="cd-user-modal-container">
			<div class="cd-form">
<?php endif; ?>

<?php if ($recipient === '') : ?>
	<div id="<?php echo $contactId; ?>" class="je_contact">
		<span class="error"><?php echo Text::_('MOD_EASYQUICKCONTACT_ERROR_NO_RECIPIENT'); ?></span>
	</div>
<?php else : ?>
	<div id="<?php echo $contactId; ?>" class="je_contact">
		<?php if (!empty($emailSent)) : ?>
			<span class="success"><strong><?php echo Text::_('MOD_EASYQUICKCONTACT_SUCCESS_TITLE'); ?></strong> <?php echo Text::_('MOD_EASYQUICKCONTACT_SUCCESS_BODY'); ?></span>
		<?php else : ?>
			<?php if (!empty($mailError)) : ?>
				<span class="error"><?php echo Text::_('MOD_EASYQUICKCONTACT_ERROR_SEND'); ?></span>
			<?php endif; ?>
			<?php if (!empty($errors['token'])) : ?>
				<span class="error"><?php echo htmlspecialchars($errors['token'], ENT_QUOTES, 'UTF-8'); ?></span>
			<?php endif; ?>

			<p><?php echo htmlspecialchars($intro, ENT_QUOTES, 'UTF-8'); ?></p>

			<form id="<?php echo $formId; ?>" class="eqc-form" action="<?php echo htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" method="post" novalidate>
				<div class="input">
					<label id="je_hide" for="<?php echo $formId; ?>-name"><?php echo htmlspecialchars($labelName, ENT_QUOTES, 'UTF-8'); ?></label>
					<input
						type="text"
						name="je_name"
						id="<?php echo $formId; ?>-name"
						value="<?php echo htmlspecialchars($postedName, ENT_QUOTES, 'UTF-8'); ?>"
						class="requiredField"
						placeholder="<?php echo htmlspecialchars($labelName, ENT_QUOTES, 'UTF-8'); ?>"
						data-eqc-error-empty="<?php echo htmlspecialchars(Text::_('MOD_EASYQUICKCONTACT_ERROR_NAME'), ENT_QUOTES, 'UTF-8'); ?>"
						required
					/>
					<?php if (!empty($errors['name'])) : ?>
						<span class="error"><?php echo htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8'); ?></span>
					<?php endif; ?>
				</div>

				<div class="input">
					<label id="je_hide" for="<?php echo $formId; ?>-email"><?php echo htmlspecialchars($labelEmail, ENT_QUOTES, 'UTF-8'); ?></label>
					<input
						type="email"
						name="je_email"
						id="<?php echo $formId; ?>-email"
						value="<?php echo htmlspecialchars($postedEmail, ENT_QUOTES, 'UTF-8'); ?>"
						class="email requiredField"
						placeholder="<?php echo htmlspecialchars($labelEmail, ENT_QUOTES, 'UTF-8'); ?>"
						data-eqc-error-empty="<?php echo htmlspecialchars(Text::_('MOD_EASYQUICKCONTACT_ERROR_EMAIL'), ENT_QUOTES, 'UTF-8'); ?>"
						data-eqc-error-invalid="<?php echo htmlspecialchars(Text::_('MOD_EASYQUICKCONTACT_ERROR_EMAIL_INVALID'), ENT_QUOTES, 'UTF-8'); ?>"
						required
					/>
					<?php if (!empty($errors['email'])) : ?>
						<span class="error"><?php echo htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8'); ?></span>
					<?php endif; ?>
				</div>

				<div class="input">
					<label id="je_hide" for="<?php echo $formId; ?>-phone"><?php echo htmlspecialchars($labelPhone, ENT_QUOTES, 'UTF-8'); ?></label>
					<input
						type="text"
						name="je_phone"
						id="<?php echo $formId; ?>-phone"
						value="<?php echo htmlspecialchars($postedPhone, ENT_QUOTES, 'UTF-8'); ?>"
						class="phone1"
						placeholder="<?php echo htmlspecialchars($labelPhone, ENT_QUOTES, 'UTF-8'); ?>"
					/>
				</div>

				<div class="input">
					<label id="je_hide" for="<?php echo $formId; ?>-message"><?php echo htmlspecialchars($labelMessage, ENT_QUOTES, 'UTF-8'); ?></label>
					<textarea
						name="je_message"
						id="<?php echo $formId; ?>-message"
						class="requiredField"
						rows="6"
						placeholder="<?php echo htmlspecialchars($labelMessage, ENT_QUOTES, 'UTF-8'); ?>"
						data-eqc-error-empty="<?php echo htmlspecialchars(Text::_('MOD_EASYQUICKCONTACT_ERROR_MESSAGE'), ENT_QUOTES, 'UTF-8'); ?>"
						required
					><?php echo htmlspecialchars($postedMessage, ENT_QUOTES, 'UTF-8'); ?></textarea>
					<?php if (!empty($errors['message'])) : ?>
						<span class="error"><?php echo htmlspecialchars($errors['message'], ENT_QUOTES, 'UTF-8'); ?></span>
					<?php endif; ?>
				</div>

				<?php if ($captchaEnabled) : ?>
					<div class="input">
						<label for="<?php echo $formId; ?>-captcha"><?php echo htmlspecialchars($labelCaptcha, ENT_QUOTES, 'UTF-8'); ?></label>:
						<?php echo (int) $captchaN1; ?> + <?php echo (int) $captchaN2; ?> =
						<input
							type="text"
							class="requiredCaptcha"
							name="je_captcha"
							id="<?php echo $formId; ?>-captcha"
							value=""
							inputmode="numeric"
							data-eqc-error-empty="<?php echo htmlspecialchars(Text::_('MOD_EASYQUICKCONTACT_ERROR_CAPTCHA'), ENT_QUOTES, 'UTF-8'); ?>"
							required
						/>
						<?php if (!empty($errors['captcha'])) : ?>
							<span class="error"><?php echo htmlspecialchars($errors['captcha'], ENT_QUOTES, 'UTF-8'); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="input">
					<button name="submit" type="submit" class="je_button"><?php echo htmlspecialchars($labelSubmit, ENT_QUOTES, 'UTF-8'); ?></button>
					<input type="hidden" name="eqc_submitted" value="1" />
					<input type="hidden" name="eqc_module_id" value="<?php echo (int) $moduleId; ?>" />
					<?php echo HTMLHelper::_('form.token'); ?>
				</div>
			</form>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ($popUp) : ?>
			</div>
		</div>
	</div>
<?php endif; ?>
</div>

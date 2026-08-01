(function () {
	'use strict';

	function closest(el, selector) {
		return el && el.closest ? el.closest(selector) : null;
	}

	function openModal(modal) {
		if (!modal) {
			return;
		}
		modal.classList.add('is-visible');
		document.documentElement.classList.add('eqc-modal-open');
	}

	function closeModal(modal) {
		if (!modal) {
			return;
		}
		modal.classList.remove('is-visible');
		if (!document.querySelector('.cd-user-modal.is-visible')) {
			document.documentElement.classList.remove('eqc-modal-open');
		}
	}

	document.addEventListener('click', function (event) {
		var trigger = closest(event.target, '.qcbutton a.cd-signup, .qcbutton a.je_button');
		if (trigger) {
			event.preventDefault();
			var wrap = closest(trigger, '.mod-easyquickcontact');
			var modal = wrap ? wrap.querySelector('[data-eqc-modal]') : null;
			openModal(modal);
			return;
		}

		var modalEl = closest(event.target, '[data-eqc-modal]');
		if (modalEl && (event.target === modalEl || event.target.classList.contains('cd-close-form'))) {
			closeModal(modalEl);
		}
	});

	document.addEventListener('keyup', function (event) {
		if (event.key !== 'Escape') {
			return;
		}
		document.querySelectorAll('[data-eqc-modal].is-visible').forEach(closeModal);
	});

	function isValidatable(field) {
		return field
			&& field.classList
			&& (field.classList.contains('requiredField') || field.classList.contains('requiredCaptcha'));
	}

	function clearFieldError(field) {
		field.classList.remove('invalid');
		var wrap = closest(field, '.input') || field.parentNode;
		if (!wrap) {
			return;
		}
		wrap.querySelectorAll('.error').forEach(function (node) {
			node.remove();
		});
	}

	function addError(field, message) {
		field.classList.add('invalid');
		var wrap = closest(field, '.input') || field.parentNode;
		if (!wrap) {
			return;
		}
		var span = document.createElement('span');
		span.className = 'error eqc-js-error';
		span.textContent = message;
		wrap.appendChild(span);
	}

	function isValidEmail(value) {
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
	}

	function extractPhoneDigits(value) {
		var digits = String(value || '').replace(/\D/g, '');

		if (digits.charAt(0) === '8') {
			digits = '7' + digits.slice(1);
		}

		if (digits.charAt(0) !== '7') {
			digits = '7' + digits;
		}

		return digits.slice(0, 11);
	}

	function formatRuPhone(digits) {
		var rest = digits.slice(1);
		var out = '+7';

		if (rest.length > 0) {
			out += ' (' + rest.slice(0, Math.min(3, rest.length));
		}

		if (rest.length >= 3) {
			out += ')';
		}

		if (rest.length > 3) {
			out += ' ' + rest.slice(3, Math.min(6, rest.length));
		}

		if (rest.length > 6) {
			out += '-' + rest.slice(6, Math.min(8, rest.length));
		}

		if (rest.length > 8) {
			out += '-' + rest.slice(8, Math.min(10, rest.length));
		}

		return out;
	}

	function applyPhoneMask(field) {
		var formatted = formatRuPhone(extractPhoneDigits(field.value));
		if (field.value !== formatted) {
			field.value = formatted;
		}
	}

	function isValidPhone(value) {
		return /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/.test(value)
			|| /^7\d{10}$/.test(extractPhoneDigits(value));
	}

	function isPhoneEmpty(value) {
		var digits = extractPhoneDigits(value);
		return !value || value === '+7' || digits === '7';
	}

	function validateField(field) {
		if (!isValidatable(field)) {
			return true;
		}

		clearFieldError(field);
		var value = (field.value || '').trim();

		if (field.classList.contains('eqc-phone')) {
			if (isPhoneEmpty(value)) {
				addError(field, field.getAttribute('data-eqc-error-empty') || '');
				return false;
			}

			if (!isValidPhone(value)) {
				addError(field, field.getAttribute('data-eqc-error-invalid') || '');
				return false;
			}

			return true;
		}

		if (!value) {
			addError(field, field.getAttribute('data-eqc-error-empty') || '');
			return false;
		}

		if (field.classList.contains('email') && !isValidEmail(value)) {
			addError(field, field.getAttribute('data-eqc-error-invalid') || '');
			return false;
		}

		return true;
	}

	function validateForm(form) {
		var hasError = false;
		form.querySelectorAll('.requiredField, .requiredCaptcha').forEach(function (field) {
			if (!validateField(field)) {
				hasError = true;
			}
		});
		return !hasError;
	}

	document.addEventListener('focusin', function (event) {
		var field = event.target;
		if (!field || !field.classList || !field.classList.contains('eqc-phone')) {
			return;
		}
		if (!closest(field, 'form.eqc-form')) {
			return;
		}
		if (isPhoneEmpty(field.value)) {
			field.value = '+7';
		}
	});

	document.addEventListener('input', function (event) {
		var field = event.target;
		if (!field || !closest(field, 'form.eqc-form')) {
			return;
		}

		if (field.classList.contains('eqc-phone')) {
			applyPhoneMask(field);
		}

		if (!isValidatable(field)) {
			return;
		}

		if (field.dataset.eqcTouched === '1' || field.classList.contains('invalid')) {
			validateField(field);
		}
	});

	document.addEventListener('focusout', function (event) {
		var field = event.target;
		if (!isValidatable(field) || !closest(field, 'form.eqc-form')) {
			return;
		}
		field.dataset.eqcTouched = '1';
		validateField(field);
	});

	document.addEventListener('submit', function (event) {
		var form = event.target;
		if (!form || !form.classList || !form.classList.contains('eqc-form')) {
			return;
		}

		form.querySelectorAll('.eqc-phone').forEach(applyPhoneMask);
		form.querySelectorAll('.requiredField, .requiredCaptcha').forEach(function (field) {
			field.dataset.eqcTouched = '1';
		});

		if (!validateForm(form)) {
			event.preventDefault();
		}
	});

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.mod-easyquickcontact[data-eqc-force-modal="1"]').forEach(function (wrap) {
			openModal(wrap.querySelector('[data-eqc-modal]'));
		});

		document.querySelectorAll('form.eqc-form .eqc-phone').forEach(function (field) {
			if (isPhoneEmpty(field.value)) {
				field.value = '+7';
			} else {
				applyPhoneMask(field);
			}
		});
	});
})();

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

	function clearFieldErrors(form) {
		form.querySelectorAll('.error.eqc-js-error').forEach(function (node) {
			node.remove();
		});
		form.querySelectorAll('.invalid').forEach(function (node) {
			node.classList.remove('invalid');
		});
	}

	function addError(field, message) {
		field.classList.add('invalid');
		var span = document.createElement('span');
		span.className = 'error eqc-js-error';
		span.textContent = message;
		field.parentNode.appendChild(span);
	}

	function isValidEmail(value) {
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
	}

	document.addEventListener('submit', function (event) {
		var form = event.target;
		if (!form || !form.classList || !form.classList.contains('eqc-form')) {
			return;
		}

		clearFieldErrors(form);
		var hasError = false;

		form.querySelectorAll('.requiredField').forEach(function (field) {
			var value = (field.value || '').trim();
			var label = field.getAttribute('placeholder') || 'field';

			if (!value) {
				addError(field, 'Please enter your ' + label + '!');
				hasError = true;
				return;
			}

			if (field.classList.contains('email') && !isValidEmail(value)) {
				addError(field, "You've entered an invalid " + label + '!');
				hasError = true;
			}
		});

		form.querySelectorAll('.requiredCaptcha').forEach(function (field) {
			if (!(field.value || '').trim()) {
				var label = field.previousElementSibling
					? field.previousElementSibling.textContent.replace(/:$/, '').trim()
					: 'Captcha';
				addError(field, 'Please enter the correct ' + label + '!');
				hasError = true;
			}
		});

		if (hasError) {
			event.preventDefault();
		}
	});
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.mod-easyquickcontact[data-eqc-force-modal="1"]').forEach(function (wrap) {
			openModal(wrap.querySelector('[data-eqc-modal]'));
		});
	});
})();

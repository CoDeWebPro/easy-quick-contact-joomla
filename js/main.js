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

	function validateField(field) {
		if (!isValidatable(field)) {
			return true;
		}

		clearFieldError(field);
		var value = (field.value || '').trim();

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

	document.addEventListener('input', function (event) {
		var field = event.target;
		if (!isValidatable(field) || !closest(field, 'form.eqc-form')) {
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
	});
})();

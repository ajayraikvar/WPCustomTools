(function () {
	'use strict';

	function countKeywords(value) {
		return String(value || '')
			.split(/[\r\n,]+/)
			.map(function (part) {
				return part.trim();
			})
			.filter(function (part, index, all) {
				return part !== '' && all.findIndex(function (item) {
					return item.toLocaleLowerCase() === part.toLocaleLowerCase();
				}) === index;
			}).length;
	}

	function copyText(textarea, status) {
		var text = textarea.value.trim();
		if (!text) {
			status.textContent = 'There are no keywords to copy.';
			return;
		}

		function fallbackCopy() {
			textarea.focus();
			textarea.select();
			var copied = false;
			try {
				copied = document.execCommand('copy');
			} catch (error) {
				copied = false;
			}
			status.textContent = copied ? 'Keywords copied.' : 'Select the text and copy it manually.';
		}

		if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
			navigator.clipboard.writeText(text).then(function () {
				status.textContent = 'Keywords copied.';
			}).catch(fallbackCopy);
		} else {
			fallbackCopy();
		}
	}

	function init() {
		var boxes = document.querySelectorAll('.wpct-keywords-tool');
		boxes.forEach(function (box) {
			var textarea = box.querySelector('.wpct-additional-keywords-input');
			var count = box.querySelector('.wpct-keywords-count');
			var copy = box.querySelector('.wpct-copy-keywords');
			var status = box.querySelector('.wpct-copy-status');

			if (!textarea || !count || !copy || !status || box.dataset.wpctKeywordsReady === '1') {
				return;
			}
			box.dataset.wpctKeywordsReady = '1';

			function refreshCount() {
				var number = countKeywords(textarea.value);
				count.textContent = number + (number === 1 ? ' phrase' : ' phrases');
			}

			textarea.addEventListener('input', refreshCount);
			copy.addEventListener('click', function () {
				copyText(textarea, status);
			});
			refreshCount();
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());

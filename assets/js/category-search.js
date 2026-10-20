(function () {
	'use strict';

	function normalize(value) {
		return String(value || '').toLocaleLowerCase().trim();
	}

	function getCategoryLabel(item) {
		var label = item.querySelector(':scope > label');
		if (label) {
			return normalize(label.textContent);
		}

		var checkbox = item.querySelector(':scope > input[type="checkbox"]');
		if (checkbox && checkbox.nextSibling) {
			return normalize(checkbox.nextSibling.textContent);
		}

		return normalize(item.textContent);
	}

	function addSearchField(box) {
		if (!box || box.dataset.wpctSearchReady === '1') {
			return;
		}

		var list = box.querySelector('.categorychecklist');
		if (!list) {
			return;
		}

		box.dataset.wpctSearchReady = '1';

		var wrapper = document.createElement('p');
		wrapper.className = 'wpct-category-search-wrap';

		var input = document.createElement('input');
		input.type = 'search';
		input.className = 'wpct-category-search';
		input.placeholder = 'Search product categories…';
		input.setAttribute('aria-label', 'Search product categories');
		input.setAttribute('autocomplete', 'off');
		input.setAttribute('spellcheck', 'false');

		var status = document.createElement('span');
		status.className = 'screen-reader-text wpct-category-search-status';
		status.setAttribute('aria-live', 'polite');

		wrapper.appendChild(input);
		wrapper.appendChild(status);
		box.insertBefore(wrapper, list);

		function filterItems() {
			var query = normalize(input.value);
			var items = Array.prototype.slice.call(list.querySelectorAll('li'));
			var matches = 0;

			items.forEach(function (item) {
				item.classList.remove('wpct-search-hidden', 'wpct-search-match');
			});

			if (!query) {
				status.textContent = '';
				return;
			}

			items.forEach(function (item) {
				if (getCategoryLabel(item).indexOf(query) !== -1) {
					matches += 1;
					item.classList.add('wpct-search-match');

					var parent = item.parentElement;
					while (parent && parent !== list) {
						if (parent.tagName === 'LI') {
							parent.classList.add('wpct-search-match');
							parent.classList.remove('wpct-search-hidden');
						}
						parent = parent.parentElement;
					}
				}
			});

			items.forEach(function (item) {
				if (!item.classList.contains('wpct-search-match') && !item.querySelector('li.wpct-search-match')) {
					item.classList.add('wpct-search-hidden');
				}
			});

			status.textContent = matches + (matches === 1 ? ' category found.' : ' categories found.');
		}

		input.addEventListener('input', filterItems);
	}

	function init() {
		var box = document.getElementById('product_catdiv');
		if (box) {
			addSearchField(box);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());

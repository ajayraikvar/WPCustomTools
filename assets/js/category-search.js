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

	function getAllCategoriesPanel(box) {
		// WordPress taxonomy meta boxes use the "-all" tab for the full taxonomy tree
		// and the "-pop" tab for "Most Used". Never bind the search to the latter.
		return box.querySelector('#product_cat-all') ||
			box.querySelector('.tabs-panel[id$="-all"]') ||
			null;
	}

	function getAllCategoriesList(box, panel) {
		if (panel) {
			var panelList = panel.querySelector('.categorychecklist');
			if (panelList) {
				return panelList;
			}
		}

		// WordPress's stable default ID for the full product category checklist.
		var allList = box.querySelector('#product_catchecklist');
		if (allList) {
			return allList;
		}

		// Defensive fallback for markup variations: explicitly exclude the Most Used
		// list, whose WordPress ID normally ends in "-pop".
		var lists = Array.prototype.slice.call(box.querySelectorAll('.categorychecklist'));
		for (var i = 0; i < lists.length; i += 1) {
			if (!/-pop$/.test(lists[i].id || '') && (!panel || !lists[i].closest('.tabs-panel[id$="-pop"]'))) {
				return lists[i];
			}
		}

		return null;
	}

	function addSearchField(box) {
		if (!box || box.dataset.wpctSearchReady === '1') {
			return;
		}

		var panel = getAllCategoriesPanel(box);
		var list = getAllCategoriesList(box, panel);
		if (!list || !list.parentNode) {
			return;
		}

		// Do not mark the metabox initialized until a usable full category list exists.
		box.dataset.wpctSearchReady = '1';

		var wrapper = document.createElement('p');
		wrapper.className = 'wpct-category-search-wrap';

		var input = document.createElement('input');
		input.type = 'search';
		input.className = 'wpct-category-search';
		input.placeholder = 'Search all product categories…';
		input.setAttribute('aria-label', 'Search all product categories');
		input.setAttribute('autocomplete', 'off');
		input.setAttribute('spellcheck', 'false');

		var status = document.createElement('span');
		status.className = 'screen-reader-text wpct-category-search-status';
		status.setAttribute('aria-live', 'polite');

		wrapper.appendChild(input);
		wrapper.appendChild(status);

		// Insert within the All categories panel; never insert/filter inside Most Used.
		list.parentNode.insertBefore(wrapper, list);

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

					// Reveal ancestor category rows so nested matches stay navigable.
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

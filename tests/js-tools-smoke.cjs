'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

class FakeClassList {
	constructor() { this.values = new Set(); }
	add(...names) { names.forEach((name) => this.values.add(name)); }
	remove(...names) { names.forEach((name) => this.values.delete(name)); }
	contains(name) { return this.values.has(name); }
}

function makeListItem(label) {
	const item = {
		tagName: 'LI',
		label: { textContent: label },
		parentElement: null,
		classList: new FakeClassList(),
		children: [],
		querySelector(selector) {
			if (selector === ':scope > label') return this.label;
			if (selector === ':scope > input[type="checkbox"]') return null;
			if (selector === 'li.wpct-search-match') {
				return this.children.find((child) => child.classList.contains('wpct-search-match') || child.querySelector('li.wpct-search-match')) || null;
			}
			return null;
		}
	};
	return item;
}

function makeElement(tagName) {
	return {
		tagName: String(tagName).toUpperCase(),
		className: '',
		dataset: {},
		children: [],
		attributes: {},
		listeners: {},
		textContent: '',
		value: '',
		placeholder: '',
		appendChild(child) { this.children.push(child); child.parentElement = this; return child; },
		setAttribute(name, value) { this.attributes[name] = value; },
		addEventListener(name, callback) { this.listeners[name] = callback; }
	};
}

function runCategorySearchSmokeTest() {
	const allList = {
		id: 'product_catchecklist',
		parentNode: null,
		items: [],
		querySelectorAll(selector) { return selector === 'li' ? this.items : []; }
	};
	const mostUsedList = { id: 'product_catchecklist-pop', touched: false };
	const panel = {
		inserted: null,
		querySelector(selector) { return selector === '.categorychecklist' ? allList : null; },
		insertBefore(node, reference) {
			assert.equal(reference, allList, 'search input must be inserted before the All categories list');
			this.inserted = node;
		}
	};
	allList.parentNode = panel;

	const parent = makeListItem('IGNOU Assignments');
	const child = makeListItem('AFW-1');
	const unrelated = makeListItem('RGPV');
	parent.children.push(child);
	child.parentElement = parent;
	parent.parentElement = allList;
	unrelated.parentElement = allList;
	allList.items = [parent, child, unrelated];

	const root = {
		dataset: {},
		querySelector(selector) {
			if (selector === '#product_cat-all') return panel;
			if (selector === '#product_catchecklist') return allList;
			return null;
		},
		querySelectorAll(selector) { return selector === '.categorychecklist' ? [allList, mostUsedList] : []; }
	};

	const document = {
		readyState: 'complete',
		getElementById(id) { return id === 'product_catdiv' ? root : null; },
		createElement: makeElement,
		addEventListener() {}
	};

	const source = fs.readFileSync('assets/js/category-search.js', 'utf8');
	vm.runInNewContext(source, { document, console });

	assert.ok(panel.inserted, 'search field should be inserted into the All categories panel');
	const input = panel.inserted.children[0];
	const status = panel.inserted.children[1];
	assert.equal(input.placeholder, 'Search all product categories…', 'search placeholder should identify the full category list');

	input.value = 'AFW-1';
	input.listeners.input();
	assert.equal(child.classList.contains('wpct-search-match'), true, 'matching child category should be shown');
	assert.equal(parent.classList.contains('wpct-search-match'), true, 'parent category should remain visible');
	assert.equal(unrelated.classList.contains('wpct-search-hidden'), true, 'unrelated categories should be hidden while filtering');
	assert.equal(mostUsedList.touched, false, 'Most Used list must not be mutated');
	assert.equal(status.textContent, '1 category found.', 'result count should be announced');

	input.value = '';
	input.listeners.input();
	assert.equal(unrelated.classList.contains('wpct-search-hidden'), false, 'clearing search should restore all categories');
}

async function runAdditionalKeywordsSmokeTest() {
	const textarea = makeElement('textarea');
	textarea.value = 'Keyword one\nkeyword ONE\nKeyword two';
	textarea.focus = function () {};
	textarea.select = function () {};
	const count = { textContent: '' };
	const copy = makeElement('button');
	const status = { textContent: '' };
	const box = {
		dataset: {},
		querySelector(selector) {
			return {
				'.wpct-additional-keywords-input': textarea,
				'.wpct-keywords-count': count,
				'.wpct-copy-keywords': copy,
				'.wpct-copy-status': status
			}[selector] || null;
		}
	};

	const document = {
		readyState: 'complete',
		querySelectorAll(selector) { return selector === '.wpct-keywords-tool' ? [box] : []; },
		addEventListener() {},
		execCommand() { return true; }
	};
	let copied = '';
	const navigator = { clipboard: { writeText: async (value) => { copied = value; } } };
	const source = fs.readFileSync('assets/js/additional-keywords.js', 'utf8');
	vm.runInNewContext(source, { document, navigator, console, Promise });

	assert.equal(count.textContent, '2 phrases', 'duplicate phrases should count once in the editor UI');

	textarea.value = 'One\nTwo\nThree';
	textarea.listeners.input();
	assert.equal(count.textContent, '3 phrases', 'phrase count should update when content changes');

	copy.listeners.click();
	await Promise.resolve();
	await Promise.resolve();
	assert.equal(copied, 'One\nTwo\nThree', 'Copy Keywords should copy current phrase list');
	assert.equal(status.textContent, 'Keywords copied.', 'copy result should be announced');
}

(async function () {
	runCategorySearchSmokeTest();
	await runAdditionalKeywordsSmokeTest();
	console.log('JavaScript Additional Keywords and category-search smoke tests passed.');
}()).catch((error) => {
	console.error(error);
	process.exitCode = 1;
});

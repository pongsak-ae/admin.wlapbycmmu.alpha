/**
 * Compiled inline version. (Library mode)
 */

/*jshint smarttabs:true, undef:true, latedef:true, curly:true, bitwise:true, camelcase:true */
/*globals $code */

(function(exports, undefined) {
	"use strict";

	var modules = {};

	function require(ids, callback) {
		var module, defs = [];

		for (var i = 0; i < ids.length; ++i) {
			module = modules[ids[i]] || resolve(ids[i]);
			if (!module) {
				throw 'module definition dependecy not found: ' + ids[i];
			}

			defs.push(module);
		}

		callback.apply(null, defs);
	}

	function define(id, dependencies, definition) {
		if (typeof id !== 'string') {
			throw 'invalid module definition, module id must be defined and be a string';
		}

		if (dependencies === undefined) {
			throw 'invalid module definition, dependencies must be specified';
		}

		if (definition === undefined) {
			throw 'invalid module definition, definition function must be specified';
		}

		require(dependencies, function() {
			modules[id] = definition.apply(null, arguments);
		});
	}

	function defined(id) {
		return !!modules[id];
	}

	function resolve(id) {
		var target = exports;
		var fragments = id.split(/[.\/]/);

		for (var fi = 0; fi < fragments.length; ++fi) {
			if (!target[fragments[fi]]) {
				return;
			}

			target = target[fragments[fi]];
		}

		return target;
	}

	function expose(ids) {
		for (var i = 0; i < ids.length; i++) {
			var target = exports;
			var id = ids[i];
			var fragments = id.split(/[.\/]/);

			for (var fi = 0; fi < fragments.length - 1; ++fi) {
				if (target[fragments[fi]] === undefined) {
					target[fragments[fi]] = {};
				}

				target = target[fragments[fi]];
			}

			target[fragments[fragments.length - 1]] = modules[id];
		}
	}

// Included from: js/tinymce/plugins/paste/classes/Utils.js

/**
 * Utils.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * This class contails various utility functions for the paste plugin.
 *
 * @class tinymce.pasteplugin.Clipboard
 * @private
 */
define("tinymce/pasteplugin/Utils", [
	"tinymce/util/Tools",
	"tinymce/html/DomParser",
	"tinymce/html/Schema"
], function(Tools, DomParser, Schema) {
	function filter(content, items) {
		Tools.each(items, function(v) {
			if (v.constructor == RegExp) {
				content = content.replace(v, '');
			} else {
				content = content.replace(v[0], v[1]);
			}
		});

		return content;
	}

	/**
	 * Gets the innerText of the specified element. It will handle edge cases
	 * and works better than textContent on Gecko.
	 *
	 * @param {String} html HTML string to get text from.
	 * @return {String} String of text with line feeds.
	 */
	function innerText(html) {
		var schema = new Schema(), domParser = new DomParser({}, schema), text = '';
		var shortEndedElements = schema.getShortEndedElements();
		var ignoreElements = Tools.makeMap('script noscript style textarea video audio iframe object', ' ');
		var blockElements = schema.getBlockElements();

		function walk(node) {
			var name = node.name, currentNode = node;

			if (name === 'br') {
				text += '\n';
				return;
			}

			// img/input/hr
			if (shortEndedElements[name]) {
				text += ' ';
			}

			// Ingore script, video contents
			if (ignoreElements[name]) {
				text += ' ';
				return;
			}

			if (node.type == 3) {
				text += node.value;
			}

			// Walk all children
			if (!node.shortEnded) {
				if ((node = node.firstChild)) {
					do {
						walk(node);
					} while ((node = node.next));
				}
			}

			// Add \n or \n\n for blocks or P
			if (blockElements[name] && currentNode.next) {
				text += '\n';

				if (name == 'p') {
					text += '\n';
				}
			}
		}

		html = filter(html, [
			/<!\[[^\]]+\]>/g // Conditional comments
		]);

		walk(domParser.parse(html));

		return text;
	}

	/**
	 * Trims the specified HTML by removing all WebKit fragments, all elements wrapping the body trailing BR elements etc.
	 *
	 * @param {String} html Html string to trim contents on.
	 * @return {String} Html contents that got trimmed.
	 */
	function trimHtml(html) {
		function trimSpaces(all, s1, s2) {
			// WebKit &nbsp; meant to preserve multiple spaces but instead inserted around all inline tags,
			// including the spans with inline styles created on paste
			if (!s1 && !s2) {
				return ' ';
			}

			return '\u00a0';
		}

		html = filter(html, [
			/^[\s\S]*<body[^>]*>\s*|\s*<\/body[^>]*>[\s\S]*$/g, // Remove anything but the contents within the BODY element
			/<!--StartFragment-->|<!--EndFragment-->/g, // Inner fragments (tables from excel on mac)
			[/( ?)<span class="Apple-converted-space">\u00a0<\/span>( ?)/g, trimSpaces],
			/<br>$/i // Trailing BR elements
		]);

		return html;
	}

	return {
		filter: filter,
		innerText: innerText,
		trimHtml: trimHtml
	};
});

// Included from: js/tinymce/plugins/paste/classes/Clipboard.js

/**
 * Clipboard.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * This class contains logic for getting HTML contents out of the clipboard.
 *
 * We need to make a lot of ugly hacks to get the contents out of the clipboard since
 * the W3C Clipboard API is broken in all browsers that have it: Gecko/WebKit/Blink.
 * We might rewrite this the way those API:s stabilize. Browsers doesn't handle pasting
 * from applications like Word the same way as it does when pasting into a contentEditable area
 * so we need to do lots of extra work to try to get to this clipboard data.
 *
 * Current implementation steps:
 *  1. On keydown with paste keys Ctrl+V or Shift+Insert create
 *     a paste bin element and move focus to that element.
 *  2. Wait for the browser to fire a "paste" event and get the contents out of the paste bin.
 *  3. Check if the paste was successful if true, process the HTML.
 *  (4). If the paste was unsuccessful use IE execCommand, Clipboard API, document.dataTransfer old WebKit API etc.
 *
 * @class tinymce.pasteplugin.Clipboard
 * @private
 */
define("tinymce/pasteplugin/Clipboard", [
	"tinymce/Env",
	"tinymce/util/VK",
	"tinymce/pasteplugin/Utils"
], function(Env, VK, Utils) {
	return function(editor) {
		var self = this, pasteBinElm, lastRng, keyboardPasteTimeStamp = 0, draggingInternally = false;
		var pasteBinDefaultContent = '%MCEPASTEBIN%', keyboardPastePlainTextState;

		/**
		 * Pastes the specified HTML. This means that the HTML is filtered and then
		 * inserted at the current selection in the editor. It will also fire paste events
		 * for custom user filtering.
		 *
		 * @param {String} html HTML code to paste into the current selection.
		 */
		function pasteHtml(html) {
			var args, dom = editor.dom;

			args = editor.fire('BeforePastePreProcess', {content: html}); // Internal event used by Quirks
			args = editor.fire('PastePreProcess', args);
			html = args.content;

			if (!args.isDefaultPrevented()) {
				// User has bound PastePostProcess events then we need to pass it through a DOM node
				// This is not ideal but we don't want to let the browser mess up the HTML for example
				// some browsers add &nbsp; to P tags etc
				if (editor.hasEventListeners('PastePostProcess') && !args.isDefaultPrevented()) {
					// We need to attach the element to the DOM so Sizzle selectors work on the contents
					var tempBody = dom.add(editor.getBody(), 'div', {style: 'display:none'}, html);
					args = editor.fire('PastePostProcess', {node: tempBody});
					dom.remove(tempBody);
					html = args.node.innerHTML;
				}

				if (!args.isDefaultPrevented()) {
					editor.insertContent(html, {merge: editor.settings.paste_merge_formats !== false});
				}
			}
		}

		/**
		 * Pastes the specified text. This means that the plain text is processed
		 * and converted into BR and P elements. It will fire paste events for custom filtering.
		 *
		 * @param {String} text Text to paste as the current selection location.
		 */
		function pasteText(text) {
			text = editor.dom.encode(text).replace(/\r\n/g, '\n');

			var startBlock = editor.dom.getParent(editor.selection.getStart(), editor.dom.isBlock);

			// Create start block html for example <p attr="value">
			var forcedRootBlockName = editor.settings.forced_root_block;
			var forcedRootBlockStartHtml;
			if (forcedRootBlockName) {
				forcedRootBlockStartHtml = editor.dom.createHTML(forcedRootBlockName, editor.settings.forced_root_block_attrs);
				forcedRootBlockStartHtml = forcedRootBlockStartHtml.substr(0, forcedRootBlockStartHtml.length - 3) + '>';
			}

			if ((startBlock && /^(PRE|DIV)$/.test(startBlock.nodeName)) || !forcedRootBlockName) {
				text = Utils.filter(text, [
					[/\n/g, "<br>"]
				]);
			} else {
				text = Utils.filter(text, [
					[/\n\n/g, "</p>" + forcedRootBlockStartHtml],
					[/^(.*<\/p>)(<p>)$/, forcedRootBlockStartHtml + '$1'],
					[/\n/g, "<br/>"]
				]);

				if (text.indexOf('<p>') != -1) {
					text = forcedRootBlockStartHtml + text;
				}
			}

			pasteHtml(text);
		}

		/**
		 * Creates a paste bin element as close as possible to the current caret location and places the focus inside that element
		 * so that when the real paste event occurs the contents gets inserted into this element
		 * instead of the current editor selection element.
		 */
		function createPasteBin() {
			var dom = editor.dom, body = editor.getBody();
			var viewport = editor.dom.getViewPort(editor.getWin()), scrollTop = viewport.y, top = 20;
			var scrollContainer;

			lastRng = editor.selection.getRng();

			if (editor.inline) {
				scrollContainer = editor.selection.getScrollContainer();

				// Can't always rely on scrollTop returning a useful value.
				// It returns 0 if the browser doesn't support scrollTop for the element or is non-scrollable
				if (scrollContainer && scrollContainer.scrollTop > 0) {
					scrollTop = scrollContainer.scrollTop;
				}
			}

			// Calculate top cordinate this is needed to avoid scrolling to top of document
			// We want the paste bin to be as close to the caret as possible to avoid scrolling
			if (lastRng.getClientRects) {
				var rects = lastRng.getClientRects();

				if (rects.length) {
					// Client rects gets us closes to the actual
					// caret location in for example a wrapped paragraph block
					top = scrollTop + (rects[0].top - dom.getPos(body).y);
				} else {
					top = scrollTop;

					// Check if we can find a closer location by checking the range element
					var container = lastRng.startContainer;
					if (container) {
						if (container.nodeType == 3 && container.parentNode != body) {
							container = container.parentNode;
						}

						if (container.nodeType == 1) {
							top = dom.getPos(container, scrollContainer || body).y;
						}
					}
				}
			}

			// Create a pastebin
			pasteBinElm = dom.add(editor.getBody(), 'div', {
				id: "mcepastebin",
				contentEditable: true,
				"data-mce-bogus": "all",
				style: 'position: absolute; top: ' + top + 'px;' +
					'width: 10px; height: 10px; overflow: hidden; opacity: 0'
			}, pasteBinDefaultContent);

			// Move paste bin out of sight since the controlSelection rect gets displayed otherwise on IE and Gecko
			if (Env.ie || Env.gecko) {
				dom.setStyle(pasteBinElm, 'left', dom.getStyle(body, 'direction', true) == 'rtl' ? 0xFFFF : -0xFFFF);
			}

			// Prevent focus events from bubbeling fixed FocusManager issues
			dom.bind(pasteBinElm, 'beforedeactivate focusin focusout', function(e) {
				e.stopPropagation();
			});

			pasteBinElm.focus();
			editor.selection.select(pasteBinElm, true);
		}

		/**
		 * Removes the paste bin if it exists.
		 */
		function removePasteBin() {
			if (pasteBinElm) {
				var pasteBinClone;

				// WebKit/Blink might clone the div so
				// lets make sure we remove all clones
				// TODO: Man o man is this ugly. WebKit is the new IE! Remove this if they ever fix it!
				while ((pasteBinClone = editor.dom.get('mcepastebin'))) {
					editor.dom.remove(pasteBinClone);
					editor.dom.unbind(pasteBinClone);
				}

				if (lastRng) {
					editor.selection.setRng(lastRng);
				}
			}

			pasteBinElm = lastRng = null;
		}

		/**
		 * Returns the contents of the paste bin as a HTML string.
		 *
		 * @return {String} Get the contents of the paste bin.
		 */
		function getPasteBinHtml() {
			var html = '', pasteBinClones, i, clone, cloneHtml;

			// Since WebKit/Chrome might clone the paste bin when pasting
			// for example: <img style="float: right"> we need to check if any of them contains some useful html.
			// TODO: Man o man is this ugly. WebKit is the new IE! Remove this if they ever fix it!
			pasteBinClones = editor.dom.select('div[id=mcepastebin]');
			for (i = 0; i < pasteBinClones.length; i++) {
				clone = pasteBinClones[i];

				// Pasting plain text produces pastebins in pastebinds makes sence right!?
				if (clone.firstChild && clone.firstChild.id == 'mcepastebin') {
					clone = clone.firstChild;
				}

				cloneHtml = clone.innerHTML;
				if (html != pasteBinDefaultContent) {
					html += cloneHtml;
				}
			}

			return html;
		}

		/**
		 * Gets various content types out of a datatransfer object.
		 *
		 * @param {DataTransfer} dataTransfer Event fired on paste.
		 * @return {Object} Object with mime types and data for those mime types.
		 */
		function getDataTransferItems(dataTransfer) {
			var data = {};

			if (dataTransfer) {
				// Use old WebKit/IE API
				if (dataTransfer.getData) {
					var legacyText = dataTransfer.getData('Text');
					if (legacyText && legacyText.length > 0) {
						data['text/plain'] = legacyText;
					}
				}

				if (dataTransfer.types) {
					for (var i = 0; i < dataTransfer.types.length; i++) {
						var contentType = dataTransfer.types[i];
						data[contentType] = dataTransfer.getData(contentType);
					}
				}
			}

			return data;
		}

		/**
		 * Gets various content types out of the Clipboard API. It will also get the
		 * plain text using older IE and WebKit API:s.
		 *
		 * @param {ClipboardEvent} clipboardEvent Event fired on paste.
		 * @return {Object} Object with mime types and data for those mime types.
		 */
		function getClipboardContent(clipboardEvent) {
			return getDataTransferItems(clipboardEvent.clipboardData || editor.getDoc().dataTransfer);
		}

		/**
		 * Checks if the clipboard contains image data if it does it will take that data
		 * and convert it into a data url image and paste that image at the caret location.
		 *
		 * @param  {ClipboardEvent} e Paste/drop event object.
		 * @param  {DOMRange} rng Optional rng object to move selection to.
		 * @return {Boolean} true/false if the image data was found or not.
		 */
		function pasteImageData(e, rng) {
			var dataTransfer = e.clipboardData || e.dataTransfer;

			function processItems(items) {
				var i, item, reader;

				function pasteImage() {
					if (rng) {
						editor.selection.setRng(rng);
						rng = null;
					}

					pasteHtml('<img src="' + reader.result + '">');
				}

				if (items) {
					for (i = 0; i < items.length; i++) {
						item = items[i];

						if (/^image\/(jpeg|png|gif)$/.test(item.type)) {
							reader = new FileReader();
							reader.onload = pasteImage;
							reader.readAsDataURL(item.getAsFile ? item.getAsFile() : item);

							e.preventDefault();
							return true;
						}
					}
				}
			}

			if (editor.settings.paste_data_images && dataTransfer) {
				return processItems(dataTransfer.items) || processItems(dataTransfer.files);
			}
		}

		/**
		 * Chrome on Andoid doesn't support proper clipboard access so we have no choice but to allow the browser default behavior.
		 *
		 * @param {Event} e Paste event object to check if it contains any data.
		 * @return {Boolean} true/false if the clipboard is empty or not.
		 */
		function isBrokenAndoidClipboardEvent(e) {
			var clipboardData = e.clipboardData;

			return navigator.userAgent.indexOf('Android') != -1 && clipboardData && clipboardData.items && clipboardData.items.length === 0;
		}

		function getCaretRangeFromEvent(e) {
			var doc = editor.getDoc(), rng, point;

			if (doc.caretPositionFromPoint) {
				point = doc.caretPositionFromPoint(e.clientX, e.clientY);
				rng = doc.createRange();
				rng.setStart(point.offsetNode, point.offset);
				rng.collapse(true);
			} else if (doc.caretRangeFromPoint) {
				rng = doc.caretRangeFromPoint(e.clientX, e.clientY);
			} else if (doc.body.createTextRange) {
				rng = doc.body.createTextRange();

				try {
					rng.moveToPoint(e.clientX, e.clientY);
					rng.collapse(true);
				} catch (ex) {
					// Append to top or bottom depending on drop location
					rng.collapse(e.clientY < doc.body.clientHeight);
				}
			}

			return rng;
		}

		function hasContentType(clipboardContent, mimeType) {
			return mimeType in clipboardContent && clipboardContent[mimeType].length > 0;
		}

		function isKeyboardPasteEvent(e) {
			return (VK.metaKeyPressed(e) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45);
		}

		function registerEventHandlers() {
			editor.on('keydown', function(e) {
				function removePasteBinOnKeyUp(e) {
					// Ctrl+V or Shift+Insert
					if (isKeyboardPasteEvent(e) && !e.isDefaultPrevented()) {
						removePasteBin();
					}
				}

				// Ctrl+V or Shift+Insert
				if (isKeyboardPasteEvent(e) && !e.isDefaultPrevented()) {
					keyboardPastePlainTextState = e.shiftKey && e.keyCode == 86;

					// Edge case on Safari on Mac where it doesn't handle Cmd+Shift+V correctly
					// it fires the keydown but no paste or keyup so we are left with a paste bin
					if (keyboardPastePlainTextState && Env.webkit && navigator.userAgent.indexOf('Version/') != -1) {
						return;
					}

					// Prevent undoManager keydown handler from making an undo level with the pastebin in it
					e.stopImmediatePropagation();

					keyboardPasteTimeStamp = new Date().getTime();

					// IE doesn't support Ctrl+Shift+V and it doesn't even produce a paste event
					// so lets fake a paste event and let IE use the execCommand/dataTransfer methods
					if (Env.ie && keyboardPastePlainTextState) {
						e.preventDefault();
						editor.fire('paste', {ieFake: true});
						return;
					}

					removePasteBin();
					createPasteBin();

					// Remove pastebin if we get a keyup and no paste event
					// For example pasting a file in IE 11 will not produce a paste event
					editor.once('keyup', removePasteBinOnKeyUp);
					editor.once('paste', function() {
						editor.off('keyup', removePasteBinOnKeyUp);
					});
				}
			});

			editor.on('paste', function(e) {
				var clipboardContent = getClipboardContent(e);
				var isKeyBoardPaste = new Date().getTime() - keyboardPasteTimeStamp < 1000;
				var plainTextMode = self.pasteFormat == "text" || keyboardPastePlainTextState;

				keyboardPastePlainTextState = false;

				if (e.isDefaultPrevented() || isBrokenAndoidClipboardEvent(e)) {
					removePasteBin();
					return;
				}

				if (pasteImageData(e)) {
					removePasteBin();
					return;
				}

				// Not a keyboard paste prevent default paste and try to grab the clipboard contents using different APIs
				if (!isKeyBoardPaste) {
					e.preventDefault();
				}

				// Try IE only method if paste isn't a keyboard paste
				if (Env.ie && (!isKeyBoardPaste || e.ieFake)) {
					createPasteBin();

					editor.dom.bind(pasteBinElm, 'paste', function(e) {
						e.stopPropagation();
					});

					editor.getDoc().execCommand('Paste', false, null);
					clipboardContent["text/html"] = getPasteBinHtml();
				}

				setTimeout(function() {
					var content;

					// Grab HTML from Clipboard API or paste bin as a fallback
					if (hasContentType(clipboardContent, 'text/html')) {
						content = clipboardContent['text/html'];
					} else {
						content = getPasteBinHtml();

						// If paste bin is empty try using plain text mode
						// since that is better than nothing right
						if (content == pasteBinDefaultContent) {
							plainTextMode = true;
						}
					}

					content = Utils.trimHtml(content);

					// WebKit has a nice bug where it clones the paste bin if you paste from for example notepad
					// so we need to force plain text mode in this case
					if (pasteBinElm && pasteBinElm.firstChild && pasteBinElm.firstChild.id === 'mcepastebin') {
						plainTextMode = true;
					}

					removePasteBin();

					// If we got nothing from clipboard API and pastebin then we could try the last resort: plain/text
					if (!content.length) {
						plainTextMode = true;
					}

					// Grab plain text from Clipboard API or convert existing HTML to plain text
					if (plainTextMode) {
						// Use plain text contents from Clipboard API unless the HTML contains paragraphs then
						// we should convert the HTML to plain text since works better when pasting HTML/Word contents as plain text
						if (hasContentType(clipboardContent, 'text/plain') && content.indexOf('</p>') == -1) {
							content = clipboardContent['text/plain'];
						} else {
							content = Utils.innerText(content);
						}
					}

					// If the content is the paste bin default HTML then it was
					// impossible to get the cliboard data out.
					if (content == pasteBinDefaultContent) {
						if (!isKeyBoardPaste) {
							editor.windowManager.alert('Please use Ctrl+V/Cmd+V keyboard shortcuts to paste contents.');
						}

						return;
					}

					if (plainTextMode) {
						pasteText(content);
					} else {
						pasteHtml(content);
					}
				}, 0);
			});

			editor.on('dragstart dragend', function(e) {
				draggingInternally = e.type == 'dragstart';
			});

			editor.on('drop', function(e) {
				var rng = getCaretRangeFromEvent(e);

				if (e.isDefaultPrevented() || draggingInternally) {
					return;
				}

				if (pasteImageData(e, rng)) {
					return;
				}

				if (rng && editor.settings.paste_filter_drop !== false) {
					var dropContent = getDataTransferItems(e.dataTransfer);
					var content = dropContent['mce-internal'] || dropContent['text/html'] || dropContent['text/plain'];

					if (content) {
						e.preventDefault();

						editor.undoManager.transact(function() {
							if (dropContent['mce-internal']) {
								editor.execCommand('Delete');
							}

							editor.selection.setRng(rng);

							content = Utils.trimHtml(content);

							if (!dropContent['text/html']) {
								pasteText(content);
							} else {
								pasteHtml(content);
							}
						});
					}
				}
			});

			editor.on('dragover dragend', function(e) {
				var i, dataTransfer = e.dataTransfer;

				if (editor.settings.paste_data_images && dataTransfer) {
					for (i = 0; i < dataTransfer.types.length; i++) {
						// Prevent default if we have files dragged into the editor since the pasteImageData handles that
						if (dataTransfer.types[i] == "Files") {
							e.preventDefault();
							return false;
						}
					}
				}
			});
		}

		self.pasteHtml = pasteHtml;
		self.pasteText = pasteText;

		editor.on('preInit', function() {
			registerEventHandlers();

			// Remove all data images from paste for example from Gecko
			// except internal images like video elements
			editor.parser.addNodeFilter('img', function(nodes) {
				if (!editor.settings.paste_data_images) {
					var i = nodes.length;

					while (i--) {
						var src = nodes[i].attributes.map.src;

						// Some browsers automatically produce data uris on paste
						// Safari on Mac produces webkit-fake-url see: https://bugs.webkit.org/show_bug.cgi?id=49141
						if (src && /^(data:image|webkit\-fake\-url)/.test(src)) {
							if (!nodes[i].attr('data-mce-object') && src !== Env.transparentSrc) {
								nodes[i].remove();
							}
						}
					}
				}
			});
		});
	};
});

// Included from: js/tinymce/plugins/paste/classes/WordFilter.js

/**
 * WordFilter.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * This class parses word HTML into proper TinyMCE markup.
 *
 * @class tinymce.pasteplugin.Quirks
 * @private
 */
define("tinymce/pasteplugin/WordFilter", [
	"tinymce/util/Tools",
	"tinymce/html/DomParser",
	"tinymce/html/Schema",
	"tinymce/html/Serializer",
	"tinymce/html/Node",
	"tinymce/pasteplugin/Utils"
], function(Tools, DomParser, Schema, Serializer, Node, Utils) {
	/**
	 * Checks if the specified content is from any of the following sources: MS Word/Office 365/Google docs.
	 */
	function isWordContent(content) {
		return (
			(/<font face="Times New Roman"|class="?Mso|style="[^"]*\bmso-|style='[^'']*\bmso-|w:WordDocument/i).test(content) ||
			(/class="OutlineElement/).test(content) ||
			(/id="?docs\-internal\-guid\-/.test(content))
		);
	}

	/**
	 * Checks if the specified text starts with "1. " or "a. " etc.
	 */
	function isNumericList(text) {
		var found, patterns;

		patterns = [
			/^[IVXLMCD]{1,2}\.[ \u00a0]/,  // Roman upper case
			/^[ivxlmcd]{1,2}\.[ \u00a0]/,  // Roman lower case
			/^[a-z]{1,2}[\.\)][ \u00a0]/,  // Alphabetical a-z
			/^[A-Z]{1,2}[\.\)][ \u00a0]/,  // Alphabetical A-Z
			/^[0-9]+\.[ \u00a0]/,          // Numeric lists
			/^[\u3007\u4e00\u4e8c\u4e09\u56db\u4e94\u516d\u4e03\u516b\u4e5d]+\.[ \u00a0]/, // Japanese
			/^[\u58f1\u5f10\u53c2\u56db\u4f0d\u516d\u4e03\u516b\u4e5d\u62fe]+\.[ \u00a0]/  // Chinese
		];

		text = text.replace(/^[\u00a0 ]+/, '');

		Tools.each(patterns, function(pattern) {
			if (pattern.test(text)) {
				found = true;
				return false;
			}
		});

		return found;
	}

	function isBulletList(text) {
		return /^[\s\u00a0]*[\u2022\u00b7\u00a7\u00d8\u25CF]\s*/.test(text);
	}

	function WordFilter(editor) {
		var settings = editor.settings;

		editor.on('BeforePastePreProcess', function(e) {
			var content = e.content, retainStyleProperties, validStyles;

			retainStyleProperties = settings.paste_retain_style_properties;
			if (retainStyleProperties) {
				validStyles = Tools.makeMap(retainStyleProperties.split(/[, ]/));
			}

			/**
			 * Converts fake bullet and numbered lists to real semantic OL/UL.
			 *
			 * @param {tinymce.html.Node} node Root node to convert children of.
			 */
			function convertFakeListsToProperLists(node) {
				var currentListNode, prevListNode, lastLevel = 1;

				function getText(node) {
					var txt = '';

					if (node.type === 3) {
						return node.value;
					}

					if ((node = node.firstChild)) {
						do {
							txt += getText(node);
						} while ((node = node.next));
					}

					return txt;
				}

				function trimListStart(node, regExp) {
					if (node.type === 3) {
						if (regExp.test(node.value)) {
							node.value = node.value.replace(regExp, '');
							return false;
						}
					}

					if ((node = node.firstChild)) {
						do {
							if (!trimListStart(node, regExp)) {
								return false;
							}
						} while ((node = node.next));
					}

					return true;
				}

				function removeIgnoredNodes(node) {
					if (node._listIgnore) {
						node.remove();
						return;
					}

					if ((node = node.firstChild)) {
						do {
							removeIgnoredNodes(node);
						} while ((node = node.next));
					}
				}

				function convertParagraphToLi(paragraphNode, listName, start) {
					var level = paragraphNode._listLevel || lastLevel;

					// Handle list nesting
					if (level != lastLevel) {
						if (level < lastLevel) {
							// Move to parent list
							if (currentListNode) {
								currentListNode = currentListNode.parent.parent;
							}
						} else {
							// Create new list
							prevListNode = currentListNode;
							currentListNode = null;
						}
					}

					if (!currentListNode || currentListNode.name != listName) {
						prevListNode = prevListNode || currentListNode;
						currentListNode = new Node(listName, 1);

						if (start > 1) {
							currentListNode.attr('start', '' + start);
						}

						paragraphNode.wrap(currentListNode);
					} else {
						currentListNode.append(paragraphNode);
					}

					paragraphNode.name = 'li';

					// Append list to previous list if it exists
					if (level > lastLevel && prevListNode) {
						prevListNode.lastChild.append(currentListNode);
					}

					lastLevel = level;

					// Remove start of list item "1. " or "&middot; " etc
					removeIgnoredNodes(paragraphNode);
					trimListStart(paragraphNode, /^\u00a0+/);
					trimListStart(paragraphNode, /^\s*([\u2022\u00b7\u00a7\u00d8\u25CF]|\w+\.)/);
					trimListStart(paragraphNode, /^\u00a0+/);
				}

				var paragraphs = node.getAll('p');

				for (var i = 0; i < paragraphs.length; i++) {
					node = paragraphs[i];

					if (node.name == 'p' && node.firstChild) {
						// Find first text node in paragraph
						var nodeText = getText(node);

						// Detect unordered lists look for bullets
						if (isBulletList(nodeText)) {
							convertParagraphToLi(node, 'ul');
							continue;
						}

						// Detect ordered lists 1., a. or ixv.
						if (isNumericList(nodeText)) {
							// Parse OL start number
							var matches = /([0-9])\./.exec(nodeText);
							var start = 1;
							if (matches) {
								start = parseInt(matches[1], 10);
							}

							convertParagraphToLi(node, 'ol', start);
							continue;
						}

						// Convert paragraphs marked as lists but doesn't look like anything
						if (node._listLevel) {
							convertParagraphToLi(node, 'ul', 1);
							continue;
						}

						currentListNode = null;
					}
				}
			}

			function filterStyles(node, styleValue) {
				var outputStyles = {}, matches, styles = editor.dom.parseStyle(styleValue);

				Tools.each(styles, function(value, name) {
					// Convert various MS styles to W3C styles
					switch (name) {
						case 'mso-list':
							// Parse out list indent level for lists
							matches = /\w+ \w+([0-9]+)/i.exec(styleValue);
							if (matches) {
								node._listLevel = parseInt(matches[1], 10);
							}

							// Remove these nodes <span style="mso-list:Ignore">o</span>
							// Since the span gets removed we mark the text node and the span
							if (/Ignore/i.test(value) && node.firstChild) {
								node._listIgnore = true;
								node.firstChild._listIgnore = true;
							}

							break;

						case "horiz-align":
							name = "text-align";
							break;

						case "vert-align":
							name = "vertical-align";
							break;

						case "font-color":
						case "mso-foreground":
							name = "color";
							break;

						case "mso-background":
						case "mso-highlight":
							name = "background";
							break;

						case "font-weight":
						case "font-style":
							if (value != "normal") {
								outputStyles[name] = value;
							}
							return;

						case "mso-element":
							// Remove track changes code
							if (/^(comment|comment-list)$/i.test(value)) {
								node.remove();
								return;
							}

							break;
					}

					if (name.indexOf('mso-comment') === 0) {
						node.remove();
						return;
					}

					// Never allow mso- prefixed names
					if (name.indexOf('mso-') === 0) {
						return;
					}

					// Output only valid styles
					if (retainStyleProperties == "all" || (validStyles && validStyles[name])) {
						outputStyles[name] = value;
					}
				});

				// Convert bold style to "b" element
				if (/(bold)/i.test(outputStyles["font-weight"])) {
					delete outputStyles["font-weight"];
					node.wrap(new Node("b", 1));
				}

				// Convert italic style to "i" element
				if (/(italic)/i.test(outputStyles["font-style"])) {
					delete outputStyles["font-style"];
					node.wrap(new Node("i", 1));
				}

				// Serialize the styles and see if there is something left to keep
				outputStyles = editor.dom.serializeStyle(outputStyles, node.name);
				if (outputStyles) {
					return outputStyles;
				}

				return null;
			}

			if (settings.paste_enable_default_filters === false) {
				return;
			}

			// Detect is the contents is Word junk HTML
			if (isWordContent(e.content)) {
				e.wordContent = true; // Mark it for other processors

				// Remove basic Word junk
				content = Utils.filter(content, [
					// Word comments like conditional comments etc
					/<!--[\s\S]+?-->/gi,

					// Remove comments, scripts (e.g., msoShowComment), XML tag, VML content,
					// MS Office namespaced tags, and a few other tags
					/<(!|script[^>]*>.*?<\/script(?=[>\s])|\/?(\?xml(:\w+)?|img|meta|link|style|\w:\w+)(?=[\s\/>]))[^>]*>/gi,

					// Convert <s> into <strike> for line-though
					[/<(\/?)s>/gi, "<$1strike>"],

					// Replace nsbp entites to char since it's easier to handle
					[/&nbsp;/gi, "\u00a0"],

					// Convert <span style="mso-spacerun:yes">___</span> to string of alternating
					// breaking/non-breaking spaces of same length
					[/<span\s+style\s*=\s*"\s*mso-spacerun\s*:\s*yes\s*;?\s*"\s*>([\s\u00a0]*)<\/span>/gi,
						function(str, spaces) {
							return (spaces.length > 0) ?
								spaces.replace(/./, " ").slice(Math.floor(spaces.length / 2)).split("").join("\u00a0") : "";
						}
					]
				]);

				var validElements = settings.paste_word_valid_elements;
				if (!validElements) {
					validElements = '-strong/b,-em/i,-span,-p,-ol,-ul,-li,-h1,-h2,-h3,-h4,-h5,-h6,-p/div,' +
						'-table[width],-tr,-td[colspan|rowspan|width],-th,-thead,-tfoot,-tbody,-a[href|name],sub,sup,strike,br,del';
				}

				// Setup strict schema
				var schema = new Schema({
					valid_elements: validElements,
					valid_children: '-li[p]'
				});

				// Add style/class attribute to all element rules since the user might have removed them from
				// paste_word_valid_elements config option and we need to check them for properties
				Tools.each(schema.elements, function(rule) {
					if (!rule.attributes["class"]) {
						rule.attributes["class"] = {};
						rule.attributesOrder.push("class");
					}

					if (!rule.attributes.style) {
						rule.attributes.style = {};
						rule.attributesOrder.push("style");
					}
				});

				// Parse HTML into DOM structure
				var domParser = new DomParser({}, schema);

				// Filter styles to remove "mso" specific styles and convert some of them
				domParser.addAttributeFilter('style', function(nodes) {
					var i = nodes.length, node;

					while (i--) {
						node = nodes[i];
						node.attr('style', filterStyles(node, node.attr('style')));

						// Remove pointess spans
						if (node.name == 'span' && node.parent && !node.attributes.length) {
							node.unwrap();
						}
					}
				});

				// Check the class attribute for comments or del items and remove those
				domParser.addAttributeFilter('class', function(nodes) {
					var i = nodes.length, node, className;

					while (i--) {
						node = nodes[i];

						className = node.attr('class');
						if (/^(MsoCommentReference|MsoCommentText|msoDel|MsoCaption)$/i.test(className)) {
							node.remove();
						}

						node.attr('class', null);
					}
				});

				// Remove all del elements since we don't want the track changes code in the editor
				domParser.addNodeFilter('del', function(nodes) {
					var i = nodes.length;

					while (i--) {
						nodes[i].remove();
					}
				});

				// Keep some of the links and anchors
				domParser.addNodeFilter('a', function(nodes) {
					var i = nodes.length, node, href, name;

					while (i--) {
						node = nodes[i];
						href = node.attr('href');
						name = node.attr('name');

						if (href && href.indexOf('#_msocom_') != -1) {
							node.remove();
							continue;
						}

						if (href && href.indexOf('file://') === 0) {
							href = href.split('#')[1];
							if (href) {
								href = '#' + href;
							}
						}

						if (!href && !name) {
							node.unwrap();
						} else {
							// Remove all named anchors that aren't specific to TOC, Footnotes or Endnotes
							if (name && !/^_?(?:toc|edn|ftn)/i.test(name)) {
								node.unwrap();
								continue;
							}

							node.attr({
								href: href,
								name: name
							});
						}
					}
				});

				// Parse into DOM structure
				var rootNode = domParser.parse(content);

				// Process DOM
				convertFakeListsToProperLists(rootNode);

				// Serialize DOM back to HTML
				e.content = new Serializer({}, schema).serialize(rootNode);
			}
		});
	}

	WordFilter.isWordContent = isWordContent;

	return WordFilter;
});

// Included from: js/tinymce/plugins/paste/classes/Quirks.js

/**
 * Quirks.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * This class contains various fixes for browsers. These issues can not be feature
 * detected since we have no direct control over the clipboard. However we might be able
 * to remove some of these fixes once the browsers gets updated/fixed.
 *
 * @class tinymce.pasteplugin.Quirks
 * @private
 */
define("tinymce/pasteplugin/Quirks", [
	"tinymce/Env",
	"tinymce/util/Tools",
	"tinymce/pasteplugin/WordFilter",
	"tinymce/pasteplugin/Utils"
], function(Env, Tools, WordFilter, Utils) {
	"use strict";

	return function(editor) {
		function addPreProcessFilter(filterFunc) {
			editor.on('BeforePastePreProcess', function(e) {
				e.content = filterFunc(e.content);
			});
		}

		/**
		 * Removes BR elements after block elements. IE9 has a nasty bug where it puts a BR element after each
		 * block element when pasting from word. This removes those elements.
		 *
		 * This:
		 *  <p>a</p><br><p>b</p>
		 *
		 * Becomes:
		 *  <p>a</p><p>b</p>
		 */
		function removeExplorerBrElementsAfterBlocks(html) {
			// Only filter word specific content
			if (!WordFilter.isWordContent(html)) {
				return html;
			}

			// Produce block regexp based on the block elements in schema
			var blockElements = [];

			Tools.each(editor.schema.getBlockElements(), function(block, blockName) {
				blockElements.push(blockName);
			});

			var explorerBlocksRegExp = new RegExp(
				'(?:<br>&nbsp;[\\s\\r\\n]+|<br>)*(<\\/?(' + blockElements.join('|') + ')[^>]*>)(?:<br>&nbsp;[\\s\\r\\n]+|<br>)*',
				'g'
			);

			// Remove BR:s from: <BLOCK>X</BLOCK><BR>
			html = Utils.filter(html, [
				[explorerBlocksRegExp, '$1']
			]);

			// IE9 also adds an extra BR element for each soft-linefeed and it also adds a BR for each word wrap break
			html = Utils.filter(html, [
				[/<br><br>/g, '<BR><BR>'], // Replace multiple BR elements with uppercase BR to keep them intact
				[/<br>/g, ' '],            // Replace single br elements with space since they are word wrap BR:s
				[/<BR><BR>/g, '<br>']      // Replace back the double brs but into a single BR
			]);

			return html;
		}

		/**
		 * WebKit has a nasty bug where the all computed styles gets added to style attributes when copy/pasting contents.
		 * This fix solves that by simply removing the whole style attribute.
		 *
		 * The paste_webkit_styles option can be set to specify what to keep:
		 *  paste_webkit_styles: "none" // Keep no styles
		 *  paste_webkit_styles: "all", // Keep all of them
		 *  paste_webkit_styles: "font-weight color" // Keep specific ones
		 *
		 * @param {String} content Content that needs to be processed.
		 * @return {String} Processed contents.
		 */
		function removeWebKitStyles(content) {
			// Passthrough all styles from Word and let the WordFilter handle that junk
			if (WordFilter.isWordContent(content)) {
				return content;
			}

			// Filter away styles that isn't matching the target node
			var webKitStyles = editor.settings.paste_webkit_styles;

			if (editor.settings.paste_remove_styles_if_webkit === false || webKitStyles == "all") {
				return content;
			}

			if (webKitStyles) {
				webKitStyles = webKitStyles.split(/[, ]/);
			}

			// Keep specific styles that doesn't match the current node computed style
			if (webKitStyles) {
				var dom = editor.dom, node = editor.selection.getNode();

				content = content.replace(/(<[^>]+) style="([^"]*)"([^>]*>)/gi, function(all, before, value, after) {
					var inputStyles = dom.parseStyle(value, 'span'), outputStyles = {};

					if (webKitStyles === "none") {
						return before + after;
					}

					for (var i = 0; i < webKitStyles.length; i++) {
						var inputValue = inputStyles[webKitStyles[i]], currentValue = dom.getStyle(node, webKitStyles[i], true);

						if (/color/.test(webKitStyles[i])) {
							inputValue = dom.toHex(inputValue);
							currentValue = dom.toHex(currentValue);
						}

						if (currentValue != inputValue) {
							outputStyles[webKitStyles[i]] = inputValue;
						}
					}

					outputStyles = dom.serializeStyle(outputStyles, 'span');
					if (outputStyles) {
						return before + ' style="' + outputStyles + '"' + after;
					}

					return before + after;
				});
			} else {
				// Remove all external styles
				content = content.replace(/(<[^>]+) style="([^"]*)"([^>]*>)/gi, '$1$3');
			}

			// Keep internal styles
			content = content.replace(/(<[^>]+) data-mce-style="([^"]+)"([^>]*>)/gi, function(all, before, value, after) {
				return before + ' style="' + value + '"' + after;
			});

			return content;
		}

		// Sniff browsers and apply fixes since we can't feature detect
		if (Env.webkit) {
			addPreProcessFilter(removeWebKitStyles);
		}

		if (Env.ie) {
			addPreProcessFilter(removeExplorerBrElementsAfterBlocks);
		}
	};
});

// Included from: js/tinymce/plugins/paste/classes/Plugin.js

/**
 * Plugin.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * This class contains the tinymce plugin logic for the paste plugin.
 *
 * @class tinymce.pasteplugin.Plugin
 * @private
 */
define("tinymce/pasteplugin/Plugin", [
	"tinymce/PluginManager",
	"tinymce/pasteplugin/Clipboard",
	"tinymce/pasteplugin/WordFilter",
	"tinymce/pasteplugin/Quirks"
], function(PluginManager, Clipboard, WordFilter, Quirks) {
	var userIsInformed;

	PluginManager.add('paste', function(editor) {
		var self = this, clipboard, settings = editor.settings;

		function togglePlainTextPaste() {
			if (clipboard.pasteFormat == "text") {
				this.active(false);
				clipboard.pasteFormat = "html";
			} else {
				clipboard.pasteFormat = "text";
				this.active(true);

				if (!userIsInformed) {
					editor.windowManager.alert(
						'Paste is now in plain text mode. Contents will now ' +
						'be pasted as plain text until you toggle this option off.'
					);

					userIsInformed = true;
				}
			}
		}

		self.clipboard = clipboard = new Clipboard(editor);
		self.quirks = new Quirks(editor);
		self.wordFilter = new WordFilter(editor);

		if (editor.settings.paste_as_text) {
			self.clipboard.pasteFormat = "text";
		}

		if (settings.paste_preprocess) {
			editor.on('PastePreProcess', function(e) {
				settings.paste_preprocess.call(self, self, e);
			});
		}

		if (settings.paste_postprocess) {
			editor.on('PastePostProcess', function(e) {
				settings.paste_postprocess.call(self, self, e);
			});
		}

		editor.addCommand('mceInsertClipboardContent', function(ui, value) {
			if (value.content) {
				self.clipboard.pasteHtml(value.content);
			}

			if (value.text) {
				self.clipboard.pasteText(value.text);
			}
		});

		// Block all drag/drop events
		if (editor.paste_block_drop) {
			editor.on('dragend dragover draggesture dragdrop drop drag', function(e) {
				e.preventDefault();
				e.stopPropagation();
			});
		}

		// Prevent users from dropping data images on Gecko
		if (!editor.settings.paste_data_images) {
			editor.on('drop', function(e) {
				var dataTransfer = e.dataTransfer;

				if (dataTransfer && dataTransfer.files && dataTransfer.files.length > 0) {
					e.preventDefault();
				}
			});
		}

		editor.addButton('pastetext', {
			icon: 'pastetext',
			tooltip: 'Paste as text',
			onclick: togglePlainTextPaste,
			active: self.clipboard.pasteFormat == "text"
		});

		editor.addMenuItem('pastetext', {
			text: 'Paste as text',
			selectable: true,
			active: clipboard.pasteFormat,
			onclick: togglePlainTextPaste
		});
	});
});

expose(["tinymce/pasteplugin/Utils","tinymce/pasteplugin/WordFilter"]);
})(this);
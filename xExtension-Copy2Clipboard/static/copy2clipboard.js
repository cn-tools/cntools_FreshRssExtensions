"use strict";
/* globals context, openNotification */

var cntCopy2Clipboard = {
	init: function() {
		cnt_copy2clip_svg_path.style.fill = window.getComputedStyle(document.getElementsByClassName('btn')[0]).getPropertyValue('color');
		
        var cntClipboard = new ClipboardJS('#copy2clipboard', {
            text: function(trigger) {
                let lResult = '';
                let linkCount = 0;
                let stream = document.getElementById('stream');
                let links = stream.querySelectorAll('div.flux > ul > li.link > a');
                links.forEach(function(item, index, arr){
                    if (lResult != ''){
                        lResult = lResult + '\n';
                    }
                    lResult = lResult + item['href'];
                    linkCount++;
                }, lResult);
                trigger.setAttribute('data-lastcopycount', linkCount);
                return lResult;
            }
        });

        cntClipboard.on('success', function(e) {
            console.info('FreshRSS - Copy2Clipboard: done');
            let msg = '';
            if (e.trigger.dataset.lastcopycount >> 1){
                msg = context.extensions.copy2clipboard.messages.good_more;
                msg = msg.replace('#count#', e.trigger.dataset.lastcopycount.toString())
            } else {
                msg = context.extensions.copy2clipboard.messages.good_one;
            }
            openNotification(msg, 'good');
            e.clearSelection();
        });

        cntClipboard.on('error', function(e) {
            console.error('FreshRSS - Copy2Clipboard: Error');
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
            openNotification(context.extensions.copy2clipboard.messages.bad, 'bad');
        });

	},
};

window.onload = cntCopy2Clipboard.init;

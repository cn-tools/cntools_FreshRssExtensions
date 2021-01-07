"use strict";
/* globals context, openNotification */

var cntCopy2Clipboard = {
	init: function() {
        var cntCopySelector = '#copy2clipboard';
        var cntClipboard = new ClipboardJS(cntCopySelector, {
            text: function(trigger) {
                let linkCount = 0;
                let stream = document.getElementById('stream');
                let links = stream.querySelectorAll('div.flux > ul > li.link > a');
                let lResult = '';
                links.forEach(function(item, index, arr){
                    if (lResult != ''){
                        lResult = lResult + '\n';
                    }
                    lResult = lResult + item['href'];
                    linkCount++;
                }, lResult)
                trigger.setAttribute('data-lastcopycount', linkCount);
                return lResult;
            }
        });

        cntClipboard.on('success', function(e) {
            console.info('FreshRSS - Copy2Clipboard: done');
            let msg = '';
            if (e.trigger.dataset.lastcopycount >> 1){
                msg = context.extensions.copy2clipboard.msg_good_more;
                msg = msg.replace('#count#', e.trigger.dataset.lastcopycount.toString())
            } else {
                msg = context.extensions.copy2clipboard.msg_good_one;
            }
            openNotification(msg, 'good');
            e.clearSelection();
        });

        cntClipboard.on('error', function(e) {
            console.error('FreshRSS - Copy2Clipboard: Error');
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
            openNotification(context.extensions.copy2clipboard.msg_bad, 'bad');
        });

	},
};

window.onload = cntCopy2Clipboard.init;

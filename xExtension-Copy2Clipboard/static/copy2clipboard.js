"use strict";
/* globals context, openNotification */

var cntCopy2Clipboard = {
    init: function() {
        if ('cnt_copy2clip_svg_path' in window) {
            switch (context.extensions.copy2clipboard.config.findcolor) {
                case "1": // use selected color
                    cnt_copy2clip_svg_path.style.fill = context.extensions.copy2clipboard.config.color;
                    break;
                default: // use default by computed class `btn`
                    let elem = document.getElementsByClassName('btn')[0];
                    if (elem) {
                        cnt_copy2clip_svg_path.style.fill = window.getComputedStyle(elem).getPropertyValue('color');
                    } else {
                        console.log('copy2clipboard: no element with default class `btn` found!');
                    }
                    break;
            }

            var cntClipboard = new ClipboardJS('#copy2clipboard', {
                text: function(trigger) {
                    let lResult = '';
                    let linkCount = 0;
                    let stream = document.getElementById('stream');
                    let links = stream.querySelectorAll('div.flux > ul > li.link > a');

                    for (var i=0, n=links.length; i < n; ++i ) {
                        let item = links[i];
                        if (lResult != ''){
                            lResult = lResult + '\n';
                        }
                        lResult = lResult + item['href'];
                        linkCount++;
                    }

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
        }
    },
};

window.onload = cntCopy2Clipboard.init;

(function() {
    'use strict';
    var LtUpvote = {
        settings: {
            upvoteUrl: '/lt-upvote/:type/:id/upvote/',
            downvoteUrl: '/lt-upvote/:type/:id/downvote/',
            resetUrl: '/lt-upvote/:type/:id/reset/',
            actionMethod: 'GET',
            divs: document.querySelectorAll('div.ltu')
        },
        init: function () {
            this.bindUIActions();

        },
        bindUIActions: function () {
            for (var i = 0; i < this.settings.divs.length; i++) {
                var ltuDiv = this.settings.divs[i];
                var upvoteA = ltuDiv.querySelector('input.ltu-upvote');
                var downvoteA = ltuDiv.querySelector('input.ltu-downvote');
                if (upvoteA != null) {
                    upvoteA.onclick = this.upvoteAction;
                } else {
                    console.warn('Upvote element not found');
                }
                if (downvoteA != null) {
                    downvoteA.onclick = this.downvoteAction;
                } else {
                    console.warn('Downvote element not found');
                }
            }
        },
        upvoteAction: function (e) {
            var divLtu = e.target.parentNode.parentNode;
            var counter = divLtu.querySelector('span.ltu-counter');
            var action = 'upvote';
            var upvoteElement = e.target;
            var downvoteElement = divLtu.querySelector('input.ltu-downvote');
            var isCheckedUpvote = upvoteElement.checked;
            var isCheckedDownvote = downvoteElement.checked;
            upvoteElement.checked = false;
            downvoteElement.checked = false;
            upvoteElement.checked = isCheckedUpvote;

            if (isCheckedUpvote) {
                counter.innerText++;
                if (isCheckedDownvote) {
                    counter.innerText++;
                }
            } else {
                counter.innerText--;
                action = 'reset';
            }
            LtUpvote.dispatchCustomEvent(counter, action);
            LtUpvote.performBackendAction(action, counter.dataset.ltuId);
        },
        downvoteAction: function (e) {
            var divLtu = e.target.parentNode.parentNode;
            var counter = divLtu.querySelector('span.ltu-counter');
            var action = 'downvote';
            var upvoteElement = divLtu.querySelector('input.ltu-upvote');
            var downvoteElement = e.target;
            var isCheckedUpvote = upvoteElement.checked;
            var isCheckedDownvote = downvoteElement.checked;
            upvoteElement.checked = false;
            downvoteElement.checked = false;
            downvoteElement.checked = isCheckedDownvote;

            if (isCheckedDownvote) {
                counter.innerText--;
                if (isCheckedUpvote) {
                    counter.innerText--;
                }
            } else {
                counter.innerText++;
                action = 'reset';
            }
            LtUpvote.dispatchCustomEvent(counter, action);
            LtUpvote.performBackendAction(action, counter.dataset.ltuType, counter.dataset.ltuId);
        },
        dispatchCustomEvent: function (counter, action) {
            var event = new CustomEvent('ltu', {
                detail: {
                    id: counter.dataset.ltuId,
                    type: counter.dataset.ltuType,
                    counter: counter.innerText,
                    action: action,
                    anonymous: counter.dataset.ltuAnonymous
                }
            });
            dispatchEvent(event);
        },
        performBackendAction: function (action, type, id) {
            if (action === 'upvote' && this.settings.upvoteUrl !== null) {
                this.callUrl(this.renderUrl(this.settings.upvoteUrl, type, id));
            }
            if (action === 'downvote' && this.settings.downvoteUrl !== null) {
                this.callUrl(this.renderUrl(this.settings.downvoteUrl, type, id));
            }
            if (action === 'reset' && this.settings.resetUrl !== null) {
                this.callUrl(this.renderUrl(this.settings.resetUrl, type, id));
            }
        },
        callUrl: function (url) {
            var xhttp = new XMLHttpRequest();
            xhttp.open(this.settings.actionMethod, url, true);
            xhttp.send();
        },
        renderUrl: function (url, type, id) {
            return url.replace(':id', id).replace(':type', type);
        },
        clearSelection: function () {
            if (window.getSelection) {
                window.getSelection().removeAllRanges();
            } else if (document.selection) {
                document.selection.empty();
            }
        }
    };

    this.ltupvote = LtUpvote;
}).call(window);
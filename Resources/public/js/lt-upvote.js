(function() {
    'use strict';
    var LtUpvote = {
        settings: {
            upvoteUrl: '/lt-upvote/:id/upvote/',
            downvoteUrl: '/lt-upvote/:id/downvote/',
            resetUrl: '/lt-upvote/:id/reset/',
            actionMethod: 'GET',
            divs: document.querySelectorAll('div.ltu'),
            isUpvotedClass: 'ltu-upvote-on',
            isDownvotedClass: 'ltu-downvote-on',
            alternativeUpvoteAction: null,
            alternativeDownvoteAction: null
        },
        init: function () {            this.bindUIActions();
        },
        bindUIActions: function () {
            for (var i = 0; i < this.settings.divs.length; i++) {
                var ltuDiv = this.settings.divs[i];
                var upvoteA = ltuDiv.querySelector('a.ltu-upvote');
                var downvoteA = ltuDiv.querySelector('a.ltu-downvote');
                if (this.alternativeUpvoteAction) {
                    upvoteA.onclick = this.alternativeUpvoteAction;
                } else {
                    upvoteA.onclick = this.upvoteAction;
                }

                if (this.alternativeDownvoteAction) {
                    downvoteA.onclick = this.alternativeDownvoteAction;
                } else {
                    downvoteA.onclick = this.downvoteAction;
                }
            }
        },
        upvoteAction: function (e) {
            var counter = e.target.parentNode.querySelector('span.ltu-counter');
            var action = 'upvote';
            if (!e.target.classList.contains(LtUpvote.settings.isUpvotedClass)) {
                e.target.classList.add(LtUpvote.settings.isUpvotedClass);
                counter.innerText++;
                if (this.parentNode.querySelector('a.ltu-downvote').classList.contains(LtUpvote.settings.isDownvotedClass)) {
                    counter.innerText++;
                    this.parentNode.querySelector('a.ltu-downvote').classList.remove(LtUpvote.settings.isDownvotedClass);
                }
            } else {
                e.target.classList.remove(LtUpvote.settings.isUpvotedClass);
                counter.innerText--;
                action = 'reset';
            }
            LtUpvote.clearSelection();
            LtUpvote.performBackendAction(action, counter.dataset.ltuId);
        },
        downvoteAction: function (e) {
            var counter = e.target.parentNode.querySelector('span.ltu-counter');
            var action = 'downvote';
            if (!e.target.classList.contains(LtUpvote.settings.isDownvotedClass)) {
                e.target.classList.add(LtUpvote.settings.isDownvotedClass);
                counter.innerText--;
                if (this.parentNode.querySelector('a.ltu-upvote').classList.contains(LtUpvote.settings.isUpvotedClass)) {
                    counter.innerText--;
                    this.parentNode.querySelector('a.ltu-upvote').classList.remove(LtUpvote.settings.isUpvotedClass);
                }
            } else {
                e.target.classList.remove(LtUpvote.settings.isDownvotedClass);
                counter.innerText++;
                action = 'reset';
            }
            LtUpvote.clearSelection();
            LtUpvote.performBackendAction(action, counter.dataset.ltuId);
        },
        performBackendAction: function (action, id) {
            if (action === 'upvote' && this.settings.upvoteUrl !== null) {
                this.callUrl(this.renderUrl(this.settings.upvoteUrl, id));
            }
            if (action === 'downvote' && this.settings.downvoteUrl !== null) {
                this.callUrl(this.renderUrl(this.settings.downvoteUrl, id));
            }
            if (action === 'reset' && this.settings.resetUrl !== null) {
                this.callUrl(this.renderUrl(this.settings.resetUrl, id));
            }
        },
        callUrl: function (url) {
            var xhttp = new XMLHttpRequest();
            xhttp.open(this.settings.actionMethod, url, true);
            xhttp.send();
        },
        renderUrl: function (url, id) {
            return url.replace(':id', id);
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
$(document).ready(function () {
    newsApp.init();
    newsApp.listenAction();
});

var newsApp = {
    subscribeBtnUd : document.getElementById('subscribe_btn'),

    init: function () {
        // init scripts
    },

    listenAction: function () {
        let subscribe = this.subscribeBtnUd;
        if (subscribe) {
            subscribe.addEventListener("click", this.subscribeAction);
        }
    },

    subscribeAction: function () {
        let postId = this.dataset.postId;

        var arParams = {
            'action' : '/subscribe',
            'data' : {
                postId: postId
            }
        };

        newsApp.request(arParams, function (response, fail = false) {
            if (!fail) {
                newsApp.subscribeBtnUd.textContent = response.message;
                newsApp.subscribeBtnUd.disabled = true;
            } else {
                if ((errorMsg = response.responseJSON.errors.postId
                    || response.responseJSON.errors[0]) !== undefined) {
                    alert(errorMsg)
                }
            }
        });
    },

    request: function(params, callback) {

        if(typeof callback != 'function') {
            return;
        }

        params.action = params.action ?? location.href;
        params.method = params.method ?? 'POST';

        var request = $.ajax({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            type: 'POST',
            method: params.method,
            data: params.data,
            async: false,
            url: params.action,
            dataType: 'json',
        });
        request.done(function(response) {
            try {
                callback.call(self, response);
            } catch (e) {
                console.log('callback failed ' + e);
            }
        });

        request.fail(function(response) {
            callback.call(self, response, true);
        });
    },
}

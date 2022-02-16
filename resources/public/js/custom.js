$(document).ready(function () {
    newsApp.init();
    newsApp.listenAction();
});

var newsApp = {
    subscribeBtnUd : document.getElementById('subscribe_btn'),
    unsubscribeBtnUd : document.querySelectorAll('#unsubscribe_btn'),

    init: function () {
        // init scripts
    },

    listenAction: function () {

        if (this.subscribeBtnUd) {
            this.subscribeBtnUd.addEventListener("click", this.subscribeAction);
        }

        if (this.unsubscribeBtnUd !== undefined) {
            this.unsubscribeBtnUd.forEach((elem)=>{
                elem.addEventListener("click", this.unsubscribeAction.bind(null, null, elem), false);
            })
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

        newsApp.subscribeBtnUd.disabled = true;

        newsApp.request(arParams, function (response, fail = false) {
            if (!fail) {
                newsApp.subscribeBtnUd.textContent = response.message;
            } else {
                alert(newsApp.getErrorMessage(response));
                newsApp.subscribeBtnUd.disabled = false;
            }
        });
    },

    unsubscribeAction: function (f, ev) {
        let authorId = ev.dataset.authorId;

        var arParams = {
            'action' : '/unsubscribe',
            'data' : {
                authorId: authorId
            }
        };

        ev.disabled = true;

        newsApp.request(arParams, function (response, fail = false) {
            if (!fail) {
                ev.textContent = response.message;
            } else {
                alert(newsApp.getErrorMessage(response));
                ev.disabled = false;
            }
        });
    },

    getErrorMessage: function (response){
        if (Array.isArray(response.responseJSON.errors)) {
            var firstKey = Object.keys(data)[0];
            return response.responseJSON.errors[firstKey];
        }

        if (response.responseJSON.errors !== undefined) {
            var firstError = Object.keys(response.responseJSON.errors)[0];
            return response.responseJSON.errors[firstError][0];
        }

        if(response.responseJSON.message !== undefined) {
            return response.responseJSON.message;
        }

        return 'Server error, please try later.';
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

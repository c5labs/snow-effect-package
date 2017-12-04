(function($) {
    "use strict";

    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

    // Listen to message from child window
    eventer(messageEvent,function(e) {
        var key = e.message ? "message" : "data";
        var data = e[key];
       
        $('#keepInTouchForm').height(data.height);

        if ('keep-in-touch.subscribed' === data.name || 'keep-in-touch.updated' === data.name || 'keep-in-touch.problem' === data.name) {
            $('#signupContent').addClass('hidden');
            $('#backLink').html('Take me back to the add-on manager');
        }

    }, false);

    $(function(){

        $('#keepInTouchForm').prop('src', form_url);

        $('body').append('<div id="confettiWorld"></div>');

        var Confettiful = function Confettiful(el) {
        this.el = el;
        this.containerEl = null;

        this.confettiFrequency = 3;
        this.confettiColors = ['#fce18a', '#ff726d', '#b48def', '#f4306d'];
        this.confettiAnimations = ['slow', 'medium', 'fast'];

        this._setupElements();
        this._renderConfetti();
        };

        Confettiful.prototype._setupElements = function () {
        var containerEl = document.createElement('div');
        var elPosition = this.el.style.position;

        if (elPosition !== 'relative' || elPosition !== 'absolute') {
          this.el.style.position = 'relative';
        }

        containerEl.classList.add('confetti-container');

        this.el.appendChild(containerEl);

        this.containerEl = containerEl;
        };

        Confettiful.prototype._renderConfetti = function () {
        var _this = this;

        this.confettiInterval = setInterval(function () {
          var confettiEl = document.createElement('div');
          var confettiSize = Math.floor(Math.random() * 3) + 7 + 'px';
          var confettiBackground = _this.confettiColors[Math.floor(Math.random() * _this.confettiColors.length)];
          var confettiLeft = Math.floor(Math.random() * _this.el.offsetWidth) + 'px';
          var confettiAnimation = _this.confettiAnimations[Math.floor(Math.random() * _this.confettiAnimations.length)];

          confettiEl.classList.add('confetti', 'confetti--animation-' + confettiAnimation);
          confettiEl.style.left = confettiLeft;
          confettiEl.style.width = confettiSize;
          confettiEl.style.height = confettiSize;
          confettiEl.style.backgroundColor = confettiBackground;

          confettiEl.removeTimeout = setTimeout(function () {
            confettiEl.parentNode.removeChild(confettiEl);
          }, 3000);

          _this.containerEl.appendChild(confettiEl);
        }, 25);
        };

        window.confettiful = new Confettiful($('#confettiWorld')[0]);
    });
} (jQuery));
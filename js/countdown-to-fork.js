(function($) {
    'use strict';
    var clock;
    var forkHeight = 4800000;
    var getBlockCountUrl = '//explorer.nycoin.info/api/getblockcount';
    var setCountDownClock = function(secondsToGo) {
        // Instantiate a countdown FlipClock
        clock = $('.clock').FlipClock(secondsToGo, {
            clockFace: 'DailyCounter',
            countdown: true,
            showSeconds: false
        });
    };
    var setCountDownCounter = function(blocksToGo) {
        var counter = $('.counter').FlipClock(blocksToGo, {
            clockFace: 'Counter'
        });

        $('.counter').width(blocksToGo.toString().length * 70);

        setTimeout(function() {
            setInterval(function() {
                $.ajax({
                    url: getBlockCountUrl,
                    headers: {
                        'Cache-Control': 'no-cache'
                    },
                    success: function(currentBlockHeight) {
                        console.log(currentBlockHeight);
                        counter.setValue(forkHeight - currentBlockHeight);
                    },
                    dataType: 'html'
                });
            }, 30000);
        });

    };
    var calculateUsingStaticDate = function() {
        var currentDate = new Date();

        var futureDate = new Date("10/29/2018");

        // Calculate the difference in seconds between the future and current date
        return (futureDate.getTime() / 1000 - currentDate.getTime() / 1000);
    };

    // get current block height needed to calculate remaining time for countdown clock
    $.ajax({
        url: getBlockCountUrl,
        headers: {
            'Cache-Control': 'no-cache'
        },
        success: function(currentBlockHeight) {
            console.log(currentBlockHeight);
            if (isNaN(currentBlockHeight)) {
                setCountDownClock(calculateUsingStaticDate());
            } else {
                var blocksToGo = forkHeight - currentBlockHeight;
                var secondsToGo = Math.abs(blocksToGo * 30);

                setCountDownClock(secondsToGo);
                setCountDownCounter(blocksToGo);
            }
        },
        error: function() {
            console.log("falling back to hard coded fork date");
            setCountDownClock(calculateUsingStaticDate());
            setCountDownCounter(-999);
        },
        dataType: 'html'
    });
}(jQuery));

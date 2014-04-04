// ATTRIBUTION http://stv.whtly.com/2009/02/27/simple-jquery-string-padding-function/
$.strPad = function(i,l,s) {
    var o = i.toString();
    if (!s) { s = '0'; }
    while (o.length < l) {
        o = s + o;
    }
    return o;
};
// END ATTRIBUTION

startTime = null;
interval = null;

function stopInterval() {
    clearInterval(interval);
}

$.getJSON( "/pathway/timer", function( data ) {
    var time = data['timer']; // IN SECONDS

    if (isNaN(parseInt(time)) || !isFinite(time)) {return;}

    startTime = time;
    interval = window.setInterval(function () {

        var timerText = "";

        if (startTime > 60) {
            var minutes = (startTime / 60);
            var seconds = startTime % 60;
            timerText = Math.floor(minutes) + ":" + $.strPad(seconds, 2, '0') + " minutes left";
        } else {
            timerText = (startTime) + " seconds left";
        }

        $('#pathway-next-text').text(timerText);

        startTime--;

        if (startTime < 0) {
            stopInterval();
            $('#pathway-next-btn').removeAttr('disabled');
            $('#pathway-next-chevron').css('display', 'inline');
            $('#pathway-next-text').text('Next');

            bootbox.dialog({
                message: "Please log out of your current work and then click 'Next'.",
                title: "Well done!",
                buttons: {
                    main: {
                        label: "Ok",
                        className: "btn-primary",
                        callback: null
                    }
                }
            });

        }
    }, 1000);

//    window.setTimeout(function () {
//        window.location = '/pathway/next';
//    }, time*1000);
});
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
        $('#pathway-next-text').text(startTime-- + ' seconds left');
        if (startTime < 0) {
            stopInterval();
            $('#pathway-next-btn').removeAttr('disabled');
            $('#pathway-next-chevron').css('display', 'inline');
            $('#pathway-next-text').text('Next');

            bootbox.dialog({
                message: " You're ready to move on to the next step.",
                title: "Well done!",
                buttons: {
                    main: {
                        label: "Next",
                        className: "btn-primary",
                        callback: function() {
                            window.location = window.location.href + '/next';
                        }
                    }
                }
            });

        }
    }, 1000);

//    window.setTimeout(function () {
//        window.location = '/pathway/next';
//    }, time*1000);
});
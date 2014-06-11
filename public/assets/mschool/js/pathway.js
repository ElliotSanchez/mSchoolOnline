$(function(){

    $('#pathway-done').click(function(e) {

        bootbox.dialog({
            message: "Click the <span class=\"btn btn-xs\">I\'m done</span> button if you are completely finished with your work.",
            title: "Are you sure you are done?",
            buttons: {

                ok: {
                    label: "I'm done",
                    className: "btn-default",
                    callback: function () { window.location.replace("/pathway/done") }
                },
                main: {
                    label: "I'm not done",
                    className: "btn-success",
                    callback: null
                },

            }
        });

        e.stopPropagation();

        return false;

    })

});
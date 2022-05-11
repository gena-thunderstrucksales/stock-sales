function validateNumber(evt) {
    if (evt.keyCode != 8) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;

            if (theEvent.preventDefault)
                theEvent.preventDefault();
        }
    }
}

$("input").keypress(function(e) {
    if (e.which == 13) {
        var index = $("input[type='text']").index(this);
        $("input[type='text']").eq(index + 1).focus();
        e.preventDefault();
    }
});
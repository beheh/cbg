var h, m, s;
$(document).ready(function() {
    var full = $('#servertime').text().split(':');
    h = parseInt(unpad(full[0]));
    m = parseInt(unpad(full[1]));
    s = parseInt(unpad(full[2]));
    if(h >= 24) return;
    setTimeout('tick()', 1000);
});

function pad(number) {
    return (number < 10 ? '0' : '') + number;
}
            
function unpad(string) {
    if(string[0] == '0') {
        string = string.substr(1);
    }
    return string;
}

function refresh() {
    location.reload();
}

function tick() {
    s++;
    if(s >= 60) {
        m++;
        s = 0;
    }
    if(m >= 60) {
        h++;
        m = 0;
    }
    if(h >= 24) {
        h = 0;
        location.reload();
    }
    $('#servertime').text(pad(h)+':'+pad(m)+':'+pad(s));
    setTimeout('tick()', 1000);
}
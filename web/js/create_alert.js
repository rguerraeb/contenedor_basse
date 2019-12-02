function createAlert(type, message, title, selector) {
    // Remove undefined value
    if (typeof message == 'undefined') {
        message = '';
    }

    var html = '' +
        '<div class="row"><div class="col-md-12">' +
        '   <div class="alert alert-' + type + ' alert-dismissible">' +
        '        <button type="button" class="close" data-dismiss="alert">×</button>' +
        '        <strong>' + title + '</strong>' +
        '         ' + message + 
        '   </div>' +
        '</div></div>' +
    + '';

    var $html = $(html);
    $(selector).append($html);
}
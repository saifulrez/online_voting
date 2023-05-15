function alertBox(msg_title, msg_alert, icon, href) {
    Swal.fire(msg_title, msg_alert, icon).then(() => {
        if (href) {
            window.location.href = href != '' ? href : "?page=home";
        }
    });
}

function DateThai(date) {
    return new Date(date).toLocaleDateString('th-TH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
    })
}

function getUrlParams(parameter) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(parameter);
}
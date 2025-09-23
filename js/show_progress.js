// https://stackoverflow.com/a/6179869/1657502
var getProgressInterval;

function documentReady(fn) {
    // https://stackoverflow.com/a/7053197/1657502
    // https://youmightnotneedjquery.com/#ready
    if (document.readyState !== 'loading') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

async function getProgress() {
    clearInterval(getProgressInterval);

    // https://youmightnotneedjquery.com/#request
    const url = APP_URL + '/?action=get_progress&queue=' + QUEUE_KEY;
    const response = await fetch(url);

    if (!response.ok) {
        console.log('Unexpected error!')
    }

    const data = await response.json();

    setProgressBar(data.progress);

    if (!data.result) {
        getProgressInterval = setInterval(getProgress, 1000);
    } else {
        showFinishedAlert();
        showResult(data.result);
    }
}

function setProgressBar(progress) {
    const progressElement = document.getElementsByClassName('progress')[0];
    progressElement.setAttribute('aria-valuenow', progress);

    const progressBarElement = document.getElementsByClassName('progress-bar')[0];
    progressBarElement.innerText = (progress + '%');
    progressBarElement.style.width = (progress + '%');
    if (progress == 100) {
        progressBarElement.classList.remove('progress-bar-striped', 'progress-bar-animated');
        progressBarElement.classList.add('text-bg-success');
    }
}

function showFinishedAlert() {
    const alertElement = document.getElementsByClassName('alert')[0];
    alertElement.classList.add('show');
}

function showResult(result) {
    let html = '';
    result.forEach(function (element, index, array) {
        html += '<tr><td>' + element.id + '</td><td>' + element.status + '</td></tr>';
    });
    const tableBodyElement = document.getElementsByTagName('tbody')[0];
    tableBodyElement.innerHTML = html;

    const tableElement = document.getElementsByTagName('table')[0];
    tableElement.classList.remove('d-none');
}

documentReady(function() {
    getProgress();
});
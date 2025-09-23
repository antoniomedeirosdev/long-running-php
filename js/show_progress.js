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

    const progress = await response.text();
    setProgressBar(progress);

    if (progress < 100) {
        getProgressInterval = setInterval(getProgress, 1000);
    } else {
        showFinishedAlert();
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

documentReady(function() {
    getProgress();
});
     toastList = document.querySelectorAll('[toast-list]');
     dataChoices = document.querySelectorAll('[data-choices]');
     dataProvider = document.querySelectorAll('[data-provider]');

    // Append the Toastify JS script
    if (toastList.length > 0 || dataChoices.length > 0 || dataProvider.length > 0) {
     toastifyScript = document.createElement('script');
    toastifyScript.src = 'https://cdn.jsdelivr.net/npm/toastify-js';
    toastifyScript.type = 'text/javascript';
    document.head.appendChild(toastifyScript);
}

    // Append the Choices JS script
    if (dataChoices.length > 0) {
     choicesScript = document.createElement('script');
    choicesScript.src = 'assets/libs/choices.js/public/assets/scripts/choices.min.js';
    choicesScript.type = 'text/javascript';
    document.head.appendChild(choicesScript);
}

    // Append the Flatpickr script
    if (dataProvider.length > 0) {
     flatpickrScript = document.createElement('script');
    flatpickrScript.src = 'assets/libs/flatpickr/flatpickr.min.js';
    flatpickrScript.type = 'text/javascript';
    document.head.appendChild(flatpickrScript);
}

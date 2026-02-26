function getLoginValue() {
    const inpEmail = $('#feedBackEmailInp');
    const inpNumber = $('#feedBackNumberInp');
    return inpEmail.attr('aria-selected') === 'true' ? inpEmail.val() : inpNumber.val();
}

export async function submitFeedbackForm(e) {
    let url = window.location.origin;
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    e.preventDefault();

    const loginValue = getLoginValue();

    await fetch(url + '/contact-admin', {
        method: 'POST',
        body: JSON.stringify({
            _token: csrf,
            login: loginValue,
        }),
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    });

    window.location = '/';
}

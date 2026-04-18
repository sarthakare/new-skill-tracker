document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-password-toggle]');
    if (!button) {
        return;
    }

    const wrapper = button.closest('.relative');
    if (!wrapper) {
        return;
    }

    const input = wrapper.querySelector('input:not([type="hidden"])');
    if (!input || (input.type !== 'password' && input.type !== 'text')) {
        return;
    }

    const showIcon = button.querySelector('.password-toggle-show');
    const hideIcon = button.querySelector('.password-toggle-hide');
    const makeVisible = input.type === 'password';

    input.type = makeVisible ? 'text' : 'password';
    button.setAttribute('aria-pressed', makeVisible ? 'true' : 'false');
    button.setAttribute('aria-label', makeVisible ? 'Hide password' : 'Show password');
    showIcon?.classList.toggle('hidden', makeVisible);
    hideIcon?.classList.toggle('hidden', !makeVisible);
});

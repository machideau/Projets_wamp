document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        if (input.type === 'password') {
            input.type = 'text';
            this.classList.add('showing');
        } else {
            input.type = 'password';
            this.classList.remove('showing');
        }
    });
});
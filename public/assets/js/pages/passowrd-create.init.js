// Alternar visibilidade da senha
$('.auth-pass-inputgroup').each(function() {
    $(this).find('.password-addon').each(function() {
        $(this).on('click', function() {
            const passwordInput = $(this).closest('.auth-pass-inputgroup').find('.password-input');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
            } else {
                passwordInput.attr('type', 'password');
            }
        });
    });
});

// Comentado: Correspondência de senha
// const password = $('#password-input');
// const confirmPassword = $('#confirm-password-input');

// function validatePassword() {
//     if (password.val() !== confirmPassword.val()) {
//         confirmPassword[0].setCustomValidity("Passwords Don't Match");
//     } else {
//         confirmPassword[0].setCustomValidity("");
//     }
// }

// password.on('change', validatePassword);
// confirmPassword.on('keyup', validatePassword);

// Validação da senha
const passwordInput = $('#password-input-pastor');
const messageBox = $('.password-contain');
const letter = $('.pass-lower1');
const capital = $('.pass-upper');
const number = $('.pass-number');
const length = $('.pass-length');

passwordInput.on('focus', function() {
    messageBox.show();
});

passwordInput.on('blur', function() {
    messageBox.hide();
});

passwordInput.on('keyup', function() {

    console.log(passwordInput.val());

    // Validar letras minúsculas
    const lowerCaseLetters = /[a-z]/g;
    if (passwordInput.val().match(lowerCaseLetters)) {
        letter.removeClass('text-danger').addClass('text-success');
    } else {
        letter.removeClass('text-success').addClass('text-danger');
    }

    // Validar letras maiúsculas
    const upperCaseLetters = /[A-Z]/g;
    if (passwordInput.val().match(upperCaseLetters)) {
        capital.removeClass('invalid').addClass('valid');
    } else {
        capital.removeClass('valid').addClass('invalid');
    }

    // Validar números
    const numbers = /[0-9]/g;
    if (passwordInput.val().match(numbers)) {
        number.removeClass('invalid').addClass('valid');
    } else {
        number.removeClass('valid').addClass('invalid');
    }

    // Validar comprimento
    if (passwordInput.val().length >= 8) {
        length.removeClass('invalid').addClass('valid');
    } else {
        length.removeClass('valid').addClass('invalid');
    }
});

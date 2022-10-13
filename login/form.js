"use strict";

document.cookie = 'rememberme=yes;max-age=0;SameSite=strict';
document.cookie = 'user_id=null;max-age=0;SameSite=strict';

const msgWarn = document.getElementById('msgWarn');
const showMsgWarn = (msg) => {
	msgWarn.style.display = 'flex';
	msgWarn.children[1].innerHTML = msg;
	setTimeout(() => {
		msgWarn.style.display = 'none';
	}, 5000);
};

const formLogin = document.getElementById('form-login');
formLogin.addEventListener('submit', e => {
	e.preventDefault();
	const formData = new FormData(e.target);
	const rememberme = formData.get('rememberme');
	formData.delete('userRecover');

	let date = new Date();
	date.setTime(date.getTime() + 93 * 1000 * 60 * 60 * 24);
	let expires = date.toUTCString();

	if (rememberme !== null) document.cookie = `rememberme=yes;expires=${expires};SameSite=strict`;
	else document.cookie = `rememberme=no;max-age=0;SameSite=strict`;

	fetch('index.php', {
		method: 'POST',
		body: formData
	})
		.then(res => res.json())
		.then(res => {
			if (res.success) {
				if (rememberme !== null) document.cookie = `user_id=${res.user_id};expires=${expires};SameSite=strict`;
				else document.cookie = `user_id=${res.user_id};max-age=0;SameSite=strict`;
				window.location.href = '../';
			}
			else showMsgWarn(res.error);
		});
})

const viewPass = document.querySelector('.view');
let viewing = false;
viewPass.addEventListener('click', e => {
	let input = e.target.parentElement.parentElement.firstElementChild;
	viewing = !viewing;

	input.type = viewing ? 'text' : 'password';
	let icon = viewing ? 'fa-eye-slash' : 'fa-eye';
	let replaceIcon = viewing ? 'fa-eye' : 'fa-eye-slash';

	e.target.classList.replace(icon, replaceIcon);
})

const signUp = document.getElementById('signUp');
signUp.addEventListener('click', e => window.location.href = "../sign_up/");

// ===== OPTION RECOVER PASSWORD =====
const retrivePassword = document.getElementById('retrieve');
const forgetBackground = document.querySelector('.forget__background');
const forgetField = document.querySelector('.forget-password');
const sendForm = document.getElementById('send-form');

retrivePassword.addEventListener('click', e => {
	forgetField.style.display = 'flex';
	forgetBackground.style.display = 'flex';
	sendForm.type = 'button';

	setTimeout(() => {
		forgetField.style.opacity = '1';
		forgetBackground.style.opacity = '0.9';
	}, 1);

	const userRecover = document.getElementById('userRecover');
	userRecover.value = userRecover.form[0].value;
	userRecover.focus();
});

forgetBackground.addEventListener('click', e => {
	forgetField.style.opacity = '0';
	forgetBackground.style.opacity = '0';
	sendForm.type = 'submit';

	setTimeout(() => {
		forgetField.style.display = 'none';
		forgetBackground.style.display = 'none';
	}, 300);
})

const getCodeRecover = e => {
	e.preventDefault();
	const processingBackground = document.getElementById('processing');
	processingBackground.style.display = 'flex';
	setTimeout(() => processingBackground.style.opacity = '.9', 1);

	const formData = new FormData(e.target.form);
	formData.delete('user');
	formData.delete('password');
	fetch('index.php', {
		method: 'POST',
		body: formData
	})
		.then(res => res.json())
		.then(res => {
			processingBackground.style.opacity = '0';
			setTimeout(() => processingBackground.style.display = 'none', 300);

			if (res.success) {
				const msgInSend = document.querySelector('.forget__msgInSend');
				msgInSend.style.display = 'flex';
				setTimeout(() => msgInSend.style.opacity = '1', 1);
			}
			else if (res.userNotRegistered) showMsgWarn('Usuario no registrado');
			else if (res.limitExceeded) showMsgWarn('Límite de petición de código excedido.<br>Por favor intentalo mañana nuevamente');
			else if (res.success === false) showMsgWarn('Algo salió mal al enviar o crear el enlace');
			else showMsgWarn('Algo salió mal');
		});
}

const btnForgetPassword = document.getElementById('sendForgetPassword');

btnForgetPassword.addEventListener('click', e => getCodeRecover(e));
document.getElementById('resendCode').addEventListener('click', e => getCodeRecover(e));
document.getElementById('changeUser').addEventListener('click', e => {
	e.preventDefault();
	const msgInSend = document.querySelector('.forget__msgInSend');
	msgInSend.style.opacity = '0';
	setTimeout(() => msgInSend.style.display = 'none', 300);
});

// AJUST MIN HEIGHT OF MAIN CONTENT IN SCREEN;
const mainContent = document.getElementsByTagName('main')[0];
mainContent.style.minHeight = `${window.innerHeight}px`;
window.addEventListener('resize', e => {
	mainContent.style.minHeight = `${window.innerHeight}px`;
})
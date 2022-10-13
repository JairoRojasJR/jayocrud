"use strict";
// ===== FUNTIONALITY VALIDATE PASSWORD =====;
const inputs = document.querySelectorAll('.form__input-container .form__input');
const msgInvalidFormat = 'La contraseña no puede contener espacios, solo letras, puntos, guion medio, y simbolos @#';

const inputPassword = document.getElementById('password');
const inputConfirmPassword = document.getElementById('confirmPassword');

const validFieldStyle = (input,icon)=>{
	input.classList.toggle('live-valid',false);
	input.classList.toggle('live-invalid',false);

	input.classList.toggle('invalid',false);
	icon.classList.toggle('fa-circle-xmark',false);
	icon.classList.toggle('form__status-invalid',false);

	input.classList.add('valid');
	icon.classList.add('fa-circle-check');
	icon.classList.add('form__status-valid');

	let msgContainer = input.parentElement.nextElementSibling;
	msgContainer.style.display = 'none';
	msgContainer.textContent = '';
};

const invalidFieldStyle = (input,icon,msg)=>{
	input.classList.toggle('live-valid',false);
	input.classList.toggle('live-invalid',false);

	input.classList.toggle('valid',false);
	icon.classList.toggle('fa-circle-check',false);
	icon.classList.toggle('form__status-valid',false);

	input.classList.add('invalid');
	icon.classList.add('fa-circle-xmark');
	icon.classList.add('form__status-invalid');
	let msgContainer = input.parentElement.nextElementSibling;
	if(msg){
		msgContainer.style.display = 'block';
		msgContainer.innerHTML = msg;
	}
	else {
		msgContainer.style.display = 'none';
		msgContainer.textContent = ''
	}
};

const validatPasswords = (inputToCompare,iconITC,icon,input)=> {
	if(!input.value) invalidFieldStyle(input,icon,"El campo no puede estar vacío")
	else if(!(/^[\w.@#$-]+$/.test(input.value))){
		invalidFieldStyle(input,icon,msgInvalidFormat);
	}
	else if(inputToCompare.value && !(/^[\w.@#$-]+$/.test(inputToCompare.value))){
		invalidFieldStyle(inputToCompare,iconITC,msgInvalidFormat);
		invalidFieldStyle(input,icon);
	}
	else if(inputToCompare.value && inputToCompare.value !== input.value){
		invalidFieldStyle(input,icon);
		invalidFieldStyle(inputToCompare,iconITC,"La contraseña no coincide");
	}
	else if(input.id === 'confirmPassword' && !inputToCompare.value){
		invalidFieldStyle(input,icon);
		invalidFieldStyle(inputToCompare,iconITC,"No introdujo ninguna contraseña");
	}
	else if(inputToCompare.value && inputToCompare.value === input.value && /^[\w.@#$-]+$/.test(input.value)) {
		validFieldStyle(input,icon);
		validFieldStyle(inputToCompare,iconITC);
	}
}

const resetStatus = (input)=>{
	let icon = input.nextElementSibling.children[0];
	let msg = input.parentElement.nextElementSibling;

	input.classList.toggle('valid',false);
	input.classList.toggle('invalid',false);
	icon.classList.toggle('fa-circle-xmark',false);
	icon.classList.toggle('fa-circle-check',false);
	icon.classList.toggle('form__status-valid',false);
	icon.classList.toggle('form__status-invalid',false);
	msg.style.display = 'none';
	msg.textContent = '';
}

let userFocus = false;

for(let input of inputs){
	if(input.id === 'user') continue;
	input.value = '';
	input.addEventListener('change',e => {
		if(!userFocus){
			input.value = '';
			input.style.background = '#fff';
		}
	})

	input.addEventListener('focus',e => {
		let input = e.target;
		userFocus = true;
		resetStatus(input);
		let inputToCompare = input.id === 'password' ? inputConfirmPassword : inputPassword;
		if(inputToCompare.value && /^[\w.@#$-]+$/.test(inputToCompare.value)) resetStatus(inputToCompare);
	});

	input.addEventListener('blur',e =>{
		let input = e.target;
		let icon = input.nextElementSibling.children[0];

		icon.style.display = 'inline';

		let isValid = /^[\w.@#$-]+$/.test(input.value);
		if(!input.value){
			invalidFieldStyle(input,icon,"El campo no puede estar vacío");
		}
		else {
			if(isValid) validFieldStyle(input,icon); 
			else invalidFieldStyle(input,icon,msgInvalidFormat);
		}

		if(input.id === 'password'){
			let inputToCompare = inputConfirmPassword;
			let iconITC = inputToCompare.nextElementSibling.children[0];
			validatPasswords(inputToCompare,iconITC,icon,input);
		}

		if(input.id === 'confirmPassword'){
			let inputToCompare = inputPassword;
			let iconITC = inputToCompare.nextElementSibling.children[0];
			validatPasswords(inputToCompare,iconITC,icon,input);
		}
	})

	input.addEventListener('keyup', e => {
		let input = e.target;

		if(/^[\w.@#$-]+$/.test(input.value)){
			input.classList.toggle('live-invalid',false);
			input.classList.add('live-valid');
		}
		else {
			input.classList.toggle('live-valid',false);
			input.classList.add('live-invalid');
		}

		if(input.id === 'password'){
			let inputToCompare = inputConfirmPassword;

			if(inputToCompare.value.length > 0 && inputToCompare.value !== input.value){
				input.classList.toggle('live-valid',false);
				input.classList.add('live-invalid');
			}
			else if(inputToCompare.value.length > 0 && inputToCompare.value === input.value){
				input.classList.toggle('live-invalid',false);
				input.classList.add('live-valid');
			} 
		}
		else if(input.id === 'confirmPassword') {
			let inputToCompare = inputPassword;

			if(inputToCompare.value.length > 0 && inputToCompare.value !== input.value){
				input.classList.toggle('live-valid',false);
				input.classList.add('live-invalid');
			}
			else if(inputToCompare.value.value > 0 && inputToCompare.value === input.value){
				input.classList.toggle('live-invalid',false);
				input.classList.add('live-valid');
			}
			else if(!inputToCompare.value){
				input.classList.toggle('live-valid',false);
				input.classList.add('live-invalid');
			}
		}
	})
}

// ===== FUNCTIONALITY VIEW PASSWORD =====;
const viewPass = document.querySelectorAll('.view');

for(let view of viewPass) {
	view.addEventListener('click',e => {
		let icon = e.target;
		let input = icon.parentElement.parentElement.firstElementChild;

		if(icon.classList.contains('fa-eye-slash')) input.type = 'text';
		else input.type = 'password';

		icon.classList.toggle('fa-eye-slash');
		icon.classList.toggle('fa-eye');
	})
}

// ===== VALIDATE AND SEND NEW PASSWORD =====;
document.getElementById('form').addEventListener('submit',e => {
	e.preventDefault();
	inputPassword.blur();
	inputConfirmPassword.blur();
	const form = e.target;
	let invalid;
	for(let input of form.elements){
		if(input.type === 'submit') break;
		if(input.classList.contains('invalid') || !input.classList.contains('valid')) invalid = true;
		else invalid = false;
	}
	if(invalid){
		let msgWarn = document.getElementById('msgWarn');
		msgWarn.style.display = 'flex';

		setTimeout(()=>{
			msgWarn.style.display = 'none';
		},5000)
	}
	else {
		const formData = new FormData(form);

		const recoverResultBackground = document.querySelector('.form__recover-result--background');
		recoverResultBackground.style.display = 'flex';

		setTimeout(()=>{
			recoverResultBackground.style.opacity = '.7';
		},1);

		fetch('./',{
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(res => {
			console.log(res);
			const showRecoverResult = result => {
				const recoverResult = document.querySelector('.form__recover-result');
				const msg = recoverResult.children[0];
				const icon = recoverResult.children[1];

				if(result === 'success'){
					msg.textContent = 'Contraseña cambiada exitosamente. Redirigiendo al Login...';
					icon.classList.add('fa-circle-check','valid');
				}
				else {
					msg.textContent = 'Algo salió mal, asegurese de usar el mismo navegador durante el proceso de cambio de contraseña';
					icon.classList.add('fa-circle-xmark','invalid');
				};

				recoverResult.style.display = 'flex';
				setTimeout(()=>{
					recoverResult.style.opacity = '1';
				},1);
			};

			if(res.success){
				showRecoverResult('success');
				let urlLoginBack = `../login/?userRecover=${res.user}`;
				setTimeout(()=>{
					window.open(urlLoginBack,'_SELF');
				},3000)
			}
			else {
				let btnloginBack = document.getElementById('loginBack');
				showRecoverResult('unsuccess');
				btnloginBack.style.display = 'block';
				btnloginBack.addEventListener('click',e => {
					e.preventDefault();
					window.open('../login/','_SELF')
				});
			};
		});
	};
});

// ===== AJUST MIN HEIGHT OF MAIN CONTENT IN SCREEN =====;
const mainContent = document.getElementsByTagName('main')[0];
mainContent.style.minHeight = `${window.innerHeight}px`;
window.addEventListener('resize',e => {
	mainContent.style.minHeight = `${window.innerHeight}px`;
})
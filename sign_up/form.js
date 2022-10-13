const inputs = document.querySelectorAll('.form__input-container .form__input');
const btnSend = document.getElementById('send-form');
const viewPass = document.querySelectorAll('.view');

const formatToValidate = {
	name: /^[A-Z]{1}[a-z]+([\s][A-Z][a-z]+)?$/,
	lastname: /^[A-Z]{1}[a-z]+([\s][A-Z][a-z]+)?$/,
	username: /^[a-zA-Z0-9._-]+$/,
	email: /^[a-zA-Z0-9][\w-]+@[a-zA-Z0-9]([\w-])+\.[a-z]+((\.[a-z]+)+)?$/,
	password: /^[\w.@#$-]+$/,
	confirmPassword: /^[\w.@#-]+$/
};

const msgInvalidFormat = {
	name: 'Nombre inválido: solo se permite letras, cada nombre inicia con mayuscula',
	lastname: 'Apellido inválido: solo se permite letras, cada apellido inicia con mayuscula',
	username: 'Usuario inválido: solo se permite letras, números, puntos, guiones medio y bajo',
	email: 'Correo inválido, asegurate de introducir un formato de correo válido',
	password: 'La contraseña no puede contener espacios, solo letras, puntos, guion medio, y simbolos @#',
	confirmPassword: 'La contraseña ingresada no coincide'
};

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
	else if(!(formatToValidate[input.id].test(input.value))){
		invalidFieldStyle(input,icon,msgInvalidFormat['password']);
	}
	else if(inputToCompare.value && !(formatToValidate[inputToCompare.id].test(inputToCompare.value))){
		invalidFieldStyle(inputToCompare,iconITC,msgInvalidFormat['password']);
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
	else if(inputToCompare.value && inputToCompare.value === input.value && formatToValidate[input.id].test(input.value)) {
		validFieldStyle(input,icon);
		validFieldStyle(inputToCompare,iconITC);
	}
}

const resetStatus = (input)=>{
	let icon = input.nextElementSibling;
	let msg = input.parentElement.nextElementSibling;

	if(input.id === 'password' || input.id === 'confirmPassword'){
		icon = input.nextElementSibling.children[0];
	}

	input.classList.toggle('valid',false);
	input.classList.toggle('invalid',false);
	icon.classList.toggle('fa-circle-xmark',false);
	icon.classList.toggle('fa-circle-check',false);
	icon.classList.toggle('form__status-valid',false);
	icon.classList.toggle('form__status-invalid',false);
	msg.style.display = 'none';
	msg.textContent = '';
}

for(input of inputs){
	input.value = '';

	input.addEventListener('focus',e => {
		let input = e.srcElement;
		resetStatus(input);

		if(input.id === 'password' || input.id === 'confirmPassword'){
			let next = input.parentElement.parentElement.nextElementSibling.children[1].firstElementChild;
			let previous = input.parentElement.parentElement.previousElementSibling.children[1].firstElementChild;

			let inputToCompare = input.id === 'password' ? next : previous;
			if(inputToCompare.value && formatToValidate['password'].test(inputToCompare.value))
				resetStatus(inputToCompare);
		}
	});

	input.addEventListener('blur',e =>{
		let input = e.srcElement;
		let icon = input.nextElementSibling;

		if(input.id === 'name' || input.id === 'lastname' || input.id === 'username' || input.id === 'email')
			input.value = input.value.trim();
		if(input.id === 'password' || input.id === 'confirmPassword') icon = input.nextElementSibling.children[0];

		icon.style.display = 'inline';

		let isValid = formatToValidate[input.id].test(input.value);
		if(!input.value){
			invalidFieldStyle(input,icon,"El campo no puede estar vacío");
		}
		else {
			if(isValid) validFieldStyle(input,icon); 
			else invalidFieldStyle(input,icon,msgInvalidFormat[input.id]);
		}

		if(input.id === 'password'){
			let prevFieldContainer = input.parentElement.parentElement.nextElementSibling;
			let inputToCompare = prevFieldContainer.children[1].firstElementChild;
			let iconITC = inputToCompare.nextElementSibling.children[0];
			validatPasswords(inputToCompare,iconITC,icon,input);
		}

		if(input.id === 'confirmPassword'){
			let fieldContainer = input.parentElement.parentElement.previousElementSibling;
			let inputToCompare = fieldContainer.children[1].firstElementChild;
			let iconITC = inputToCompare.nextElementSibling.children[0];
			validatPasswords(inputToCompare,iconITC,icon,input);
		}
	})

	input.addEventListener('keyup', e => {
		let input = e.target;

		if(formatToValidate[input.id].test(input.value)){
			input.classList.toggle('live-invalid',false);
			input.classList.add('live-valid');
		}
		else {
			input.classList.toggle('live-valid',false);
			input.classList.add('live-invalid');
		}

		if(input.id === 'password'){
			let fieldContainer = input.parentElement.parentElement.nextElementSibling;
			let inputToCompare = fieldContainer.children[1].firstElementChild;

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
			let fieldContainer = input.parentElement.parentElement.previousElementSibling;
			let inputToCompare = fieldContainer.children[1].firstElementChild;

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

for(let view of viewPass) {
	viewing = false;
	view.addEventListener('click',e => {
		let input = e.srcElement.parentElement.parentElement.firstElementChild;
		viewing = !viewing;
		
		input.type = viewing ? 'text' : 'password';
		icon = viewing ? 'fa-eye-slash' : 'fa-eye';
	
		replaceIcon = viewing ? 'fa-eye' : 'fa-eye-slash';
		e.srcElement.classList.replace(icon,replaceIcon);
	})
}

const form = document.querySelector('.form-login');

form.addEventListener('submit', e => {
	e.preventDefault();
	
	msgWarn.style.display = 'none';

	let terms = document.getElementById('terms');
	let invalid = false;

	for(let input of inputs){
		if(!input.classList.contains('valid')){
			invalid = true;
			break;
		}
	}

	if(invalid || !(terms.checked)){
		let msgWarn = document.getElementById('msgWarn');
		msgWarn.style.display = 'flex';

		setTimeout(()=>{
			msgWarn.style.display = 'none';
		},5000)
	}
	else if(!invalid && terms.checked){
	    const processingBackground = document.getElementById('processing');
	    processingBackground.style.display = 'flex';
	    setTimeout(()=> processingBackground.style.opacity = '.9',1);
	
		const formData = new FormData(form);
		
		fetch('index.php',{
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(res => {
			processingBackground.style.opacity = '0';
			setTimeout(()=> processingBackground.style.display = 'none',300);
			
			const alreadyExists = document.getElementById('alreadyExists');

			const showAlreadyExists = (msg)=>{
				alreadyExists.style.display = 'flex';
				alreadyExists.children[1].textContent = msg;

				setTimeout(()=>{
					alreadyExists.style.display = 'none';
				},7000);
			}
			
			const sendLinkContainer = document.querySelector('.sendLink');
			const sendLinkCard = sendLinkContainer.children[1];
			const sendLinkMsgContainer = sendLinkCard.children[0];
			const sendLinkIconContainer = sendLinkCard.children[1];

			if(res.success) {
				sendLinkMsgContainer.innerHTML = '<b>Enlace</b> de verificación <b>enviado</b> a su <b>correo electrónico</b>.<br>Acceda al enlace para completar el registro';
				sendLinkIconContainer.classList.add('fa-circle-check','valid');
				sendLinkContainer.style.display = 'flex';
				setTimeout(()=> sendLinkContainer.style.opacity = '1',1);
				setTimeout(()=> sendLinkContainer.children[1].style.opacity = '1',300);

				// window.location.href = '../';
				// console.log(res);
			}
			else if(res.success === false){
				sendLinkMsgContainer.innerHTML = 'Algo salió mal al enviar el código de validación';
				sendLinkIconContainer.classList.add('fa-circle-xmark','invalid');
				sendLinkContainer.style.display = 'flex';
				setTimeout(()=> sendLinkContainer.style.opacity = '1',1);
				setTimeout(()=> sendLinkContainer.children[1].style.opacity = '1',300);
			}
			if(res.alreadyExists === 'username') {
				showAlreadyExists(`El nombre de usuario (${formData.get('username')}) ya está registrado`);
			}
			else if(res.alreadyExists === 'email') {
				showAlreadyExists(`El email (${formData.get('email')}) ya está registrado`);
			}
			else if(res.alreadyExists === 'both') {
				showAlreadyExists(`El nombre de usuario (${formData.get('username')}) y el email (${formData.get('email')}) ya están registrados`);
			}
		});
	}
});

// =========================================== PRUEBAS

"use strict";

const validateAdminContainer = document.querySelector('.adminPassContainer');
const validateAdminCard = document.querySelector('.adminPassCard');

if (validateAdminContainer) {
	setTimeout(() => validateAdminCard.style.opacity = '1', 1);
	document.getElementById('sendPasswordAdmin').addEventListener('click', e => {
		e.preventDefault();
		const password = document.getElementById('passwordAdmin').value;
		const formData = new FormData();
		formData.set('password', password);
		fetch('./', {
			method: 'POST',
			body: formData
		})
			.then(res => res.json())
			.then(res => {
				console.log(res);
				if (res.allow) {
					validateAdminContainer.style.opacity = '0';
					setTimeout(() => validateAdminContainer.style.display = 'none', 300);

					const msgSuccessContainer = document.getElementById('msgSuccessContainer');
					document.getElementById('msgSuccess').textContent = 'Bienvenido señor Admin';
					document.getElementById('msgWarnContainer').style.display = 'none';
					msgSuccessContainer.style.display = 'flex';
					setTimeout(() => msgSuccessContainer.style.display = 'none', 5000);

					const btnDisable = document.querySelectorAll('.disable');
					for (let btn of btnDisable) {
						btn.style.opacity = '1';
						btn.style.cursor = 'pointer';
					}
				}
				else showMsgWarn('Contraseña incorrecta');
			});
	});
	document.getElementById('visit').addEventListener('click', e => {
		validateAdminContainer.style.opacity = '0';
		setTimeout(() => validateAdminContainer.style.display = 'none', 300);
	});
}

const form = document.getElementById('formProyects');
const modal = document.querySelector('.modal');
const btnEdit = document.querySelectorAll('.edit');
const btnDelete = document.querySelectorAll('.delete');
const btnSaveChanges = document.querySelector('.btn-save');
const btnCancelChanges = document.querySelector('.btn-cancel');

const notProyects = () => {
	const notProyectsContainer = document.getElementById('notProyects');
	if (document.getElementById('bodyTableProyects').children.length === 0) notProyectsContainer.style.display = 'flex';
	else notProyectsContainer.style.display = 'none';
}

const removeProyect = btn => {
	btn.addEventListener('click', e => {
		const formData = new FormData();
		formData.set('delete', 'proyect');
		formData.set('id', btn.parentElement.id);
		fetch('./', {
			method: 'POST',
			body: formData
		})
			.then(res => res.json())
			.then(res => {
				if (res.success) {
					document.getElementById(res.id).parentElement.remove();
					notProyects();
				}
				else if (res.notAllowed) showMsgWarn('Usuario no autorizado');
				else console.log('algo salió mal');
			});
	})
}

const showMsgWarn = msg => {
	const msgWarnContainer = document.getElementById('msgWarnContainer');
	const msgWarn = document.getElementById('msgWarn');
	msgWarnContainer.style.display = 'flex';
	msgWarn.innerHTML = msg;
	setTimeout(() => msgWarnContainer.style.display = 'none', 5000);
}

const adjustFontSelectImg = label => {
	const porcWidthFileName = label.children[0].scrollWidth / label.clientWidth * 100;
	if (porcWidthFileName > 70) label.children[0].style.fontSize = '.7rem';
	if (porcWidthFileName > 90) label.children[0].style.fontSize = '.6rem';
	if (porcWidthFileName > 110) label.children[0].style.fontSize = '.5rem';
	if (porcWidthFileName > 130) label.children[0].style.fontSize = '.3rem';
}

const insertImg = input => {
	const file = input.files[0];
	let msgWarn = 'Formato de imagen inválido, asegurese de ingresar un archivo de imagen válido';
	if (file.type.split('/')[0] !== 'image') showMsgWarn(msgWarn);
	else {
		const label = input.previousElementSibling;
		label.children[0].textContent = file.name;
		label.children[1].style.display = 'block';

		adjustFontSelectImg(label);

		const reader = new FileReader();
		reader.readAsDataURL(file);
		reader.addEventListener('load', e => {
			input.nextElementSibling.innerHTML = `<img src="${e.currentTarget.result}">`;
			input.nextElementSibling.style.display = 'block';
		});
	}
}

const openModalUpdateProyect = e => {
	const proyect = e.target.parentElement.parentElement;
	modal.style.display = 'flex';
	setTimeout(() => modal.style.opacity = '1', 1);

	const inputName = document.getElementById('modal-name');
	const btnSelectImage = document.getElementById('modal-image').previousElementSibling;
	const inputDescription = document.getElementById('modal-description');

	const imageFullName = proyect.children[2].children[0].getAttribute('nameimg');
	const imageNotUniquePref = imageFullName.substring(imageFullName.indexOf('_') + 1, imageFullName.length);

	inputName.value = proyect.children[1].textContent;
	btnSelectImage.children[0].textContent = imageNotUniquePref;
	inputDescription.value = proyect.children[3].textContent;
	document.getElementById('proyectid').value = proyect.children[0].textContent;

	const label = document.getElementById('btnModalSelectImage');
	const input = document.getElementById('modal-image');
	const imgContainer = input.nextElementSibling;

	adjustFontSelectImg(label);
	imgContainer.innerHTML = `<img src="${proyect.children[2].children[0].src}" >`;
	imgContainer.style.display = 'block';
}

document.getElementById('image').addEventListener('change', e => {
	insertImg(e.target);
});

document.getElementById('modal-image').addEventListener('change', e => {
	insertImg(e.target);
});

form.addEventListener('submit', e => {
	e.preventDefault();

	const inputName = document.getElementById('name');
	const inputDescription = document.getElementById('description');

	let msgWarn;
	if (inputName.value.includes('\\') || inputDescription.value.includes('\\')) msgWarn = 'Los campos no pueden contener el caracter \\';
	const formData = new FormData(form);
	formData.set('insert', 'proyect');

	const name = formData.get('name');
	const image = formData.get('image');
	const description = formData.get('description');
	// if (!name || !image.size || !description) msgWarn = 'Por favor complete los campos del formulario';
	// if (image.type.split('/')[0] !== 'image' && (!image.type && image.size)) msgWarn = 'Por favor introduzca una imagen de formato válido, "jpg,png,jpeg..."';

	if (msgWarn) showMsgWarn(msgWarn);
	else {
		formData.set('name', name.replaceAll("'", "\\'"));
		formData.set('description', description.replaceAll("'", "\\'"));

		fetch('./', {
			method: 'POST',
			body: formData
		})
			.then(res => res.json())
			.then(res => {
				console.log(res);
				if (res.notAllowed) showMsgWarn('Usuario no autorizado');
				else if (res.success) {
					const tr = document.createElement('TR');
					const tdId = document.createElement('TD');
					const tdName = document.createElement('TD');
					const tdImage = document.createElement('TD');
					const imageTarget = document.createElement('IMG');
					const tdDescription = document.createElement('TD');
					const tdActions = document.createElement('TD');
					const btnEdit = document.createElement('DIV');
					const btnDelete = document.createElement('DIV');

					tdId.textContent = res.proyectid;
					tdName.textContent = formData.get('name').replaceAll('\\', '');
					tdDescription.textContent = formData.get('description').replaceAll('\\', '');
					btnEdit.textContent = 'Editar';
					btnDelete.textContent = 'Borrar';

					tdActions.id = res.proyectid;

					tdId.classList.add('td');
					tdName.classList.add('td');
					tdImage.classList.add('td');
					tdDescription.classList.add('td');
					tdActions.classList.add('td');
					btnEdit.classList.add('actions', 'edit');
					btnDelete.classList.add('actions', 'delete');
					imageTarget.setAttribute('nameimg', res.image);

					tr.appendChild(tdId);
					tr.appendChild(tdName);
					tr.appendChild(tdImage);
					tr.appendChild(tdDescription);
					tr.appendChild(tdActions);
					tdImage.appendChild(imageTarget);
					tdActions.appendChild(btnEdit);
					tdActions.appendChild(btnDelete);
					document.getElementById('bodyTableProyects').appendChild(tr);
					removeProyect(btnDelete);
					btnEdit.addEventListener('click', e => {
						openModalUpdateProyect(e);
						proyectInModal = e.target.parentElement.parentElement;
					});

					const file = formData.get('image');
					const reader = new FileReader();
					reader.readAsDataURL(file);
					reader.addEventListener('load', e => imageTarget.src = e.currentTarget.result);
				}
				notProyects();
			});
		document.getElementById('name').value = '';
		document.getElementById('btnSelectImage').children[0].textContent = 'Selecciona una imagen';
		document.getElementById('btnSelectImage').children[0].style.fontSize = '1rem';
		document.getElementById('btnSelectImage').children[1].style.display = 'none';
		document.getElementById('image').nextElementSibling.style.display = 'none';
		document.getElementById('image').value = '';
		document.getElementById('description').value = '';
	}
});

let proyectInModal;

for (const btn of btnEdit) {
	btn.addEventListener('click', e => {
		openModalUpdateProyect(e);
		proyectInModal = e.target.parentElement.parentElement;
	});
};

btnSaveChanges.addEventListener('click', e => {
	e.preventDefault();
	const inputName = document.getElementById('modal-name');
	const inputDescription = document.getElementById('modal-description');
	
	let msgWarn;
	if (inputName.value.includes('\\') || inputDescription.value.includes('\\')) msgWarn = 'Los campos no pueden contener el caracter \\';
	
	const form = document.getElementById('modal-formProyects');
	const formData = new FormData(form);
	formData.set('update', 'proyect');
	// console.log(form);
	
	const name = formData.get('name');
	const image = formData.get('image');
	const description = formData.get('description');
	if (!name || !description) msgWarn = 'Por favor complete los campos del formulario';
	
	if (msgWarn) showMsgWarn(msgWarn);
	else {
		formData.set('name', name.replaceAll("'", "\\'"));
		formData.set('description', description.replaceAll("'", "\\'"));

		fetch('./', {
			method: 'POST',
			body: formData
		})
			.then(res => res.json())
			.then(res => {
				if (res.notAllowed) showMsgWarn('Usuario no autorizado');
				else if (res.success) {
					proyectInModal.children[1].textContent = formData.get('name').replaceAll('\\', '');
					proyectInModal.children[2].children[0].src = res.currentUrlImage;
					proyectInModal.children[3].textContent = formData.get('description').replaceAll('\\', '');
					modal.style.display = 'none';
				}
			});
	}
});

btnCancelChanges.addEventListener('click', e => {
	e.preventDefault();
	modal.style.opacity = '0';
	setTimeout(() => modal.style.display = 'none', 300);
});

for (const btn of btnDelete) {
	removeProyect(btn);
	notProyects();
};
"use strict";

getUserData().then(res => {
	document.querySelector('.welcome-name').textContent = `${res.name} ${res.lastname}`;
});

const newUserContainer = document.querySelector('.newuser');
if(newUserContainer !== null){
	newUserContainer.style.opacity = '1';
	setTimeout(()=> newUserContainer.children[1].style.opacity = '1',300);
	setTimeout(()=> newUserContainer.style.opacity = '0',4000)
	setTimeout(()=> newUserContainer.style.display = 'none',4300);
}
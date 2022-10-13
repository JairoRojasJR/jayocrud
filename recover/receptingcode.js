"use strict";
// ===== PAGE RECEPTOFCODE =====;
const mainContent = document.getElementsByTagName('main')[0];
mainContent.style.minHeight = `${window.innerHeight}px`;
window.addEventListener('resize',e => {
	mainContent.style.minHeight = `${window.innerHeight}px`;
})

const recoverResultBackground = document.querySelector('.form__recover-result--background');
recoverResultBackground.style.display = 'flex';

setTimeout(()=> recoverResultBackground.style.opacity = '.7', 1);

const recoverResult = document.querySelector('.form__recover-result');
const icon = recoverResult.children[1];
icon.classList.add('fa-circle-xmark','invalid');

recoverResult.style.display = 'flex';

setTimeout(()=> recoverResult.style.opacity = '1', 500);
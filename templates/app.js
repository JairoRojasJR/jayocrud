/* ---------- NAV HEADER ----------*/
const nav = document.querySelector('.nav-header--sm');
const btnShowNav = document.getElementById('btn-shownav');
const btnHiddenNav = document.getElementById('btn-hiddennav');
const navMargin = document.getElementById('navmargin');

const getUserData = async () => {
	const req = await fetch(`/server/sessiondata.php`);
	res = await req.json();
	return res;
}


getUserData().then(data => {
	document.getElementById('headerUserName').textContent = data.username;
	document.getElementById('line-username').textContent = data.email;
})

btnShowNav.addEventListener('click', e => {
	nav.classList.add('show');
	navMargin.classList.add('nav__margin');
});

btnHiddenNav.addEventListener('click', e => {
	nav.classList.remove('show');
	navMargin.classList.remove('nav__margin');
});

navMargin.addEventListener('click', e => {
	nav.classList.remove('show');
	navMargin.classList.remove('nav__margin');
});

const index = document.querySelectorAll('.index');
const proyects = document.querySelectorAll('.proyects');
const signOff = document.querySelectorAll('.signoff');

for (link of index) link.addEventListener('click', () => window.open(`/`, '_SELF'));
for (link of proyects) link.addEventListener('click', () => window.open(`/proyects/`, '_SELF'));
for (link of signOff) link.addEventListener('click', () => window.open(`/sign_off/`, '_SELF'));
const shadowBoxes = document.querySelectorAll('#shadow-box');

function shadowRaise() {
	this.classList.add('shadow');
}

function shadowDown() {
	this.classList.remove('shadow');
}

shadowBoxes.forEach( box => box.addEventListener('mouseenter', shadowRaise) );
shadowBoxes.forEach( box => box.addEventListener('mouseleave', shadowDown) );

export { shadowRaise, shadowDown };
const shadowBoxes = document.querySelectorAll('#shadow-box');

function shadowRaise() {
	this.classList.add('shadow');
	// this.classList.add('bg-body');
	// this.classList.add('border-4');
}

function shadowDown() {
	this.classList.remove('shadow');
	// this.classList.remove('bg-body');
	// this.classList.remove('border-4');

}

shadowBoxes.forEach( box => box.addEventListener('mouseenter', shadowRaise) );
shadowBoxes.forEach( box => box.addEventListener('mouseleave', shadowDown) );

export { shadowRaise, shadowDown };
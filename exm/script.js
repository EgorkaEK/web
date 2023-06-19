let header_button = document.querySelector(".mobile_icon");
header_button.addEventListener("click", function () {
	let header = document.querySelector("header");
	if (header.classList.contains("open")) {
		close_popup();
	}
	else {
		header.classList.add("open");
		header_button.querySelector("img").src = "cancel.png";
	}
});

function close_popup() {
	document.querySelector("header").classList.remove("open");
	header_button.querySelector("img").src = "free-icon-menu-747327.png";
}
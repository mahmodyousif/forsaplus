/** @format */

// Start Header Section
let otherLinks = document.querySelector(".links li:last-child a");
otherLinks.addEventListener("click", () => {
	otherLinks.classList.toggle("open");
});
// End Header Section
// Start scroll Section
let btn = document.querySelector(".button");
window.addEventListener("scroll", () => {
	if (window.scrollY >= 500) {
		btn.style.cssText = "right:30px;bottom:50px";
	} else {
		btn.style.cssText = "right:-50px";
	}
});
btn.onclick = () => {
	this.scrollTo({
		top: 0,
		behavior: "smooth",
	});
};
// End scroll Section

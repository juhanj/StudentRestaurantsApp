"use strict";

/**
 * Save boolean value of a setting
 */
function saveSetting(element) {
	setCookie(
		element.target.name,
		Number(element.target.checked),
		360
	);
}
/**
 * Save value value of the chosen language radio button
 **/
function saveLanguage(element) {
	setCookie(
		element.target.name,
		element.target.value,
		360
	);
}
/**
 * When user clicks on the checkbox for location,
 * ask for permission, and getLocation.
 **/
function getGPSCoordinates(element) {
	//TODO: do location stuff here.
}

let foodCheckbox = document.querySelector( 'input[name="food"]' );
let kelaCheckbox = document.querySelector( 'input[name="kela"]' );
let joensuuCheckbox = document.querySelector( 'input[name="joensuu"]' );
let locationCheckbox = document.querySelector( 'input[name="location"]' );
let currentSelectedLanguage = document.querySelector("input[name='lang']:checked");
let allLanguages = document.querySelectorAll("input[name='lang']");

setCookie( foodCheckbox.name, Number(foodCheckbox.checked), 360 );
setCookie( kelaCheckbox.name, Number(kelaCheckbox.checked), 360 );
setCookie( joensuuCheckbox.name, Number(joensuuCheckbox.checked), 360 );
setCookie( locationCheckbox.name, Number(locationCheckbox.checked), 360 );
setCookie( currentSelectedLanguage.name, currentSelectedLanguage.value, 360 );

foodCheckbox.onclick = saveSetting;
kelaCheckbox.onclick = saveSetting;
joensuuCheckbox.onclick = saveSetting;

for ( let langInput of allLanguages ) {
	langInput.onclick = saveLanguage;
}


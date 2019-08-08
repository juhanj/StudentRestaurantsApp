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
function getLocation(element) {
	//TODO: do location stuff here.
}

let currentFoodSetting = document.querySelector( 'input[name="food"]' );
setCookie( currentFoodSetting.name, Number(currentFoodSetting.checked), 360 );
currentFoodSetting.addEventListener( 'click', saveSetting );

let currentKelaSetting = document.querySelector( 'input[name="kela"]' );
setCookie( currentKelaSetting.name, Number(currentKelaSetting.checked), 360 );
currentKelaSetting.addEventListener( 'click', saveSetting );

let currentLanguage = document.querySelector("input[name='lang']:checked");
setCookie( currentLanguage.name, currentLanguage.value, 360 );

document.querySelectorAll("input[name='lang']").forEach((input) => {
	// Add a listener for user made changes
	input.addEventListener('click', saveLanguage);
});

setCookie( 'location', null, 360 );

"use strict";
function setCookie(name, value, days) {
	let expires = "";
	let date;
	if (days) {
		date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie( name ) {
	let nameEQ = name + "=";
	let cookies_array = document.cookie.split(';');
	let cookie, i;
	for (i = 0; i < cookies_array.length; i++) {
		cookie = cookies_array[i];
		while ( cookie.charAt(0) === ' ' ) {
			cookie = cookie.substring(1, cookie.length);
		}
		if ( cookie.indexOf(nameEQ) === 0 ) {
			return cookie.substring(nameEQ.length, cookie.length);
		}
	}
	return null;
}

function deleteCookie(name) {
	document.cookie = name + '=; Max-Age=-1;';
}

function getLocation() {
	if (!navigator.geolocation) {
		return false;
	}

	function success( position ) {
		setCookie( "location", JSON.stringify([position.coords.latitude,position.coords.longitude]), 0 );
	}
	function error() {
		setCookie( "location", JSON.stringify(null), 0 );
	}

	navigator.geolocation.getCurrentPosition( success, error );
	return true;
}

/**
 * For detecting keyboard users (they press tab).
 * When detected, add a CSS class to body, which displays focus outlines.
 * @param {Event} e
 */
function handleFirstTab(e) {
	if (e.keyCode === 9) { // the "I am a keyboard user" key
		document.body.classList.add('user-is-tabbing');
		window.removeEventListener('keydown', handleFirstTab);
	}
}
window.addEventListener('keydown', handleFirstTab);

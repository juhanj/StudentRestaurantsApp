"use strict";

/**
 *
 * @param {string} name
 * @param value
 * @param {int} days
 */
function setCookie(name, value, days = 30) {
	let date = new Date();
	date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	let expires = "; expires=" + date.toUTCString();
	document.cookie = name + "=" + (value || "") + expires + "; path=" + WEB_PATH + ';secure;samesite=strict';
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

"use strict";

// Returns the ISO week of the date.
Date.prototype.getWeek = function() {
	let date = new Date(this.getTime());
	date.setHours(0, 0, 0, 0);
	// Thursday in current week decides the year.
	date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
	// January 4 is always in week 1.
	let week1 = new Date(date.getFullYear(), 0, 4);
	// Adjust to Thursday in week 1 and count number of weeks from date to week1.
	return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000
		- 3 + (week1.getDay() + 6) % 7) / 7);
};

/**
 * Send a JSON request to server, receive JSON back.
 * Usage: sendJSON(params).then((jsonResponse) => {});
 * @param data Changed to JSON before sending
 * @param {string} url optional, default == ./ajax-handler.php
 * @param {boolean} returnJSON
 * @returns {Promise<object>} JSON
 */
async function sendJSON ( data, url = './ajax-handler.php', returnJSON = true ) {
	let response = await fetch( url, {
		method: 'post',
		credentials: 'same-origin',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify( data )
	} );
	return (returnJSON) ? await response.json() : await response;
}

/**
 * Send a POST request to server, receive JSON back.
 * Usage: sendForm(formdata).then((jsonResponse) => {});
 * @param {FormData} data Form-element, must be an FormData object
 * @param {string} url optional, default = ./ajax-handler.php
 * @param {boolean} returnJSON
 * @returns {Promise<object>} JSON
 */
async function sendForm ( data, url = './ajax-handler.php', returnJSON = true ) {
	let response = await fetch( url, {
		method: 'post',
		credentials: 'same-origin',
		// explicitly no Content-Type with FormData
		body: data
	} );
	return (returnJSON) ? await response.json() : await response;
}

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
	let newCookie = name + "=" + (value || "") + expires + "; path=" + WEB_PATH + ';samesite=strict';
	document.cookie = newCookie;
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
		setCookie( "gps", JSON.stringify([position.coords.latitude,position.coords.longitude]), 360 );
	}
	function error() {
		setCookie( "gps", JSON.stringify(null), 360 );
	}

	navigator.geolocation.getCurrentPosition( success, error );
	return true;
}

let locationSetting = getCookie( 'location' );

if ( locationSetting == true ) {
	getLocation();
}
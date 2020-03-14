'use strict';

/******************************************************************
 * Functions
 *****************************************************************/
/**
 * @param food
 * @returns {string}
 */
function makeMenuFoodHTML ( food ) {
	let listItem = `<h3>${food.name ?? ''}</h3>`;
	let componentsString = '';
	food.components.forEach( ( comp ) => {
		componentsString += `<span>${comp}</span>`
	} );
	listItem += `<p class="food-components compact">${componentsString}`
		+ `<span class="price">${food.prices}</span></p>`;
	listItem = `<li class="food">${listItem}</li>`;

	return listItem;
}

/**
 * @param day
 * @returns {string}
 */
function makeDayMenuHTML ( day ) {
	let dayHeader = '';
	let menuList = '';

	if ( day.lunchHours !== null || day.menu !== null ) {
		if ( day.lunchHours === null ) {
			day.lunchHours = ['',''];
		}
		dayHeader = `<h2 class="day-header">${days[day.index]}, ${day.lunchHours[0]} &ndash; ${day.lunchHours[1]}</h2>`;
		if ( day.menu ) {
			day.menu.forEach( ( food ) => {
				menuList += makeMenuFoodHTML( food );
			} );
			menuList = `<ol class="menu-list">${menuList}</ol>`;
		} else {
			menuList = `<p>No menu available. (Restaurant may still be open.)</p>`
		}
	} else {
		dayHeader = `<h2 class="day-header">${days[day.index]}, &ndash;</h2>`;
		menuList = `<p>Restaurant closed.</p>`
	}

	menuList = `<section class="menu-day" data-id="${day.index}">${dayHeader}${menuList}</section>`;

	return menuList;
}

/**
 * @param {Object} menu
 * @param {Object[]} menu.week
 * @param {string} menu.week.date
 * @param {int} menu.week.index
 * @param {string[]} menu.week.lunchHours
 * @param {Object[]} menu.week.menu
 * @param {string} menu.week.menu.name
 * @param {string} menu.week.menu.prices
 * @param {string[]} menu.week.menu.components
 */
function buildMenuHTML ( menu ) {
	let weekHTML = '';
	menu.week.forEach( ( day ) => {
		if ( currentDayIndex <= day.index ) {
			weekHTML += makeDayMenuHTML( day );
			if ( day.index < 7 ) {
				weekHTML += '<hr>';
			}
		}
	} );
	return weekHTML;
}

/**
 * @param response
 */
function handleResponse ( response ) {
	let menu = JSON.parse( response.result.menu );
	menuContainer.innerHTML = buildMenuHTML( menu );

	menuContainer.hidden = false;
	loadingNotification.hidden = true;
}

/******************************************************************
 * Main code
 *****************************************************************/

let currentDayIndex = (new Date()).getDay();
let menuContainer = document.getElementById( 'menu-container' );
let loadingNotification = document.getElementById( 'loading-container' );
let updateButton = document.getElementById( 'force-update' );

let form = new FormData;
form.set( 'class', 'menu' );
form.set( 'id', restaurantID );
form.set( 'lang', language );

if ( updateNeeded ) {
	updateButton.hidden = true;
	form.set( 'request', 'check_for_update' );
	sendForm( form )
		.then( handleResponse );
}

updateButton.onclick = () => {
	menuContainer.hidden = true;
	loadingNotification.hidden = false;
	form.set( 'request', 'force_update' );
	sendForm( form )
		.then( handleResponse );
};


/* jshint esversion: 6 */
/* global tiWhiteLabelLib, console */
import Vue from 'vue';
import VueResource from 'vue-resource';

Vue.use( VueResource );

const updateFields = function ( { commit, state }, data ) {

	Vue.http( {
		url: tiWhiteLabelLib.root + '/input_save',
		method: 'POST',
		headers: { 'X-WP-Nonce': tiWhiteLabelLib.nonce },
		params: {
			'req': data.req,
		},
		body: {
			'data': data
		},
		responseType: 'json',
	} ).then( function ( response ) {
		if ( response.ok ) {
			console.log( '%c Form Saved.', 'color: #4B9BE7' );
			state.toast = {
				'message': response.body.data,
				'type': 'success'
			};
		}
	} ).catch( function ( error ) {
		state.toast = {
			'message': error.body.data,
			'type': 'error-toast'
		};
		console.log( '%c Could Not Save Data.', 'color: #E7602A' );
	} );

};

export default {
	updateFields
};
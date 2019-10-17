import SlimSelect from 'slim-select';

/**
 * Initialize all the meta box controls.
 */
export const initializeConditionals = function() {
	switchLayout();
	initRuleGroups();
	initGroupRepeater();
};

/**
 * Toggle Hooks meta field when changing Layout field to Hooks.
 */
function switchLayout() {
	let layout = document.getElementById( 'nv-custom-layout' );
	if ( layout === null ) {
		return;
	}
	layout.addEventListener(
			'change',
			function() {
				let hook = document.getElementById( 'nv-custom-hook' );

				hook.parentNode.parentNode.classList = '';
				if ( layout.value !== 'hooks' ) {
					hook.parentNode.parentNode.classList = 'hidden';
				}

				let priority = document.getElementById( 'nv-custom-priority' );
				priority.parentNode.parentNode.classList = '';
				if ( layout.value !== 'hooks' ) {
					priority.parentNode.parentNode.classList = 'hidden';
				}
			},
			false
	);
}

/**
 * Initialize the repeater logic.
 */
function initGroupRepeater() {
	let groupRepeater = document.querySelector( '.nv-add-rule-group' ),
			groupsWrap = document.querySelector( '.nv-rule-groups' );

	groupRepeater.addEventListener( 'click', function(e) {
		e.preventDefault();
		let newGroup = document.querySelector(
				'.nv-rule-group-template .nv-rule-group-wrap' ).cloneNode( true );
		initSingleRuleGroup( newGroup );
		groupsWrap.appendChild( newGroup );
		updateValues();
	} );
}

/**
 * Initialize rule groups.
 */
function initRuleGroups() {
	let ruleGroups = document.querySelectorAll(
			'.nv-rules-wrapper .nv-rule-group-wrap' );
	for ( let i = 0; i < ruleGroups.length; i++ ) {
		initSingleRuleGroup( ruleGroups[i] );
	}
}

/**
 * Initialize single rule group.
 *
 * @param group
 */
function initSingleRuleGroup(group) {
	let rules = group.querySelectorAll( '.individual-rule' ),
			remove = group.querySelector( '.nv-remove-rule-group' );
	remove.addEventListener( 'click', function(e) {
		e.preventDefault();
		group.parentNode.removeChild( group );
		updateValues();
	} );
	for ( let i = 0; i < rules.length; i++ ) {
		initRule( rules[i] );
	}
}

/**
 * Validate single rule value.
 *
 * @param ruleWrap
 * @returns {boolean}
 */
function validateRuleValues(ruleWrap) {
	let root = ruleWrap.querySelector( 'select.root-rule' ),
			condition = ruleWrap.querySelector( 'select.condition-rule' ),
			end = ruleWrap.querySelector(
					'.single-end-rule.has-data select.end-rule' );
	if (
			root.slim.selected() !== '' &&
			condition.slim.selected() !== '' &&
			end.slim.selected() !== ''
	) {
		ruleWrap.classList.add( 'valid-rule' );
		updateValues();
		return false;
	}
	ruleWrap.classList.remove( 'valid-rule' );
	updateValues();
}

/**
 * Initialize single rule.
 *
 * @param ruleWrap
 */
function initRule(ruleWrap) {
	let rootRule = ruleWrap.querySelector( '.nv-slim-select.root-rule' ),
			condition = ruleWrap.querySelector( '.nv-slim-select.condition-rule' ),
			endRules = ruleWrap.querySelectorAll( '.single-end-rule' ),
			duplicate = ruleWrap.querySelector( '.action.duplicate' ),
			remove = ruleWrap.querySelector( '.action.remove' ),
			rootRuleSelect, conditionRuleSelect;

	// Init root rule.
	rootRuleSelect = new SlimSelect( {
		select: rootRule
	} );

	rootRuleSelect.onChange = function(select) {
		let endRuleWraps = ruleWrap.querySelectorAll( '.single-end-rule' );
		for ( let i = 0; i < endRuleWraps.length; i++ ) {
			endRuleWraps[i].classList.remove( 'has-data' );
			if ( endRuleWraps[i].classList.contains( select.value ) ) {
				endRuleWraps[i].classList.add( 'has-data' );
			}
		}
		validateRuleValues( ruleWrap );
	};

	// Init condition.
	conditionRuleSelect = new SlimSelect( {
		select: condition, showSearch: false
	} );

	conditionRuleSelect.onChange = function(select) {
		validateRuleValues( ruleWrap );
	};

	// Init EndRules.
	for ( let i = 0; i < endRules.length; i++ ) {
		let rootValue = rootRuleSelect.selected();
		let endRuleSelect = new SlimSelect( {
			select: endRules[i].querySelector( '.nv-slim-select' )
		} );

		if ( rootValue !== '' && endRules[i].classList.contains( rootValue ) ) {
			endRules[i].classList.add( 'has-data' );
			validateRuleValues( ruleWrap );
		}

		endRuleSelect.onChange = function(select) {
			validateRuleValues( ruleWrap );
		};
	}

	// Init interactions.
	duplicate.addEventListener( 'click', function(e) {
		e.preventDefault();
		let newRule = document.querySelector(
				'.nv-rule-group-template .individual-rule' ).cloneNode( true );
		initRule( newRule );
		ruleWrap.parentNode.insertBefore( newRule, ruleWrap.nextSibling );
		updateValues();
	} );
	remove.addEventListener( 'click', function(e) {
		e.preventDefault();
		ruleWrap.parentNode.removeChild( ruleWrap );
		updateValues();
	} );
}

/**
 * Update conditional logic values and store them in the meta input.
 */
function updateValues() {
	let value = {},
			input = document.querySelector(
					'#custom-layout-conditional-logic.nv-conditional-meta-collector' ),
			ruleGroups = document.querySelectorAll(
					'.nv-rules-wrapper .nv-rule-group' );

	for ( let groupIndex = 0; groupIndex < ruleGroups.length; groupIndex++ ) {
		let groupValue = {},
				validRules = ruleGroups[groupIndex].querySelectorAll( '.valid-rule' );
		for ( let ruleIndex = 0; ruleIndex < validRules.length; ruleIndex++ ) {
			let root = validRules[ruleIndex].querySelector( 'select.root-rule' ),
					condition = validRules[ruleIndex].querySelector(
							'select.condition-rule' ),
					end = validRules[ruleIndex].querySelector(
							'.single-end-rule.has-data select.end-rule' );
			groupValue[ruleIndex] = {
				'root': root.slim.selected(),
				'condition': condition.slim.selected(),
				'end': end.slim.selected()
			};
		}
		if ( Object.keys( groupValue ).length > 0 ) {
			value[groupIndex] = groupValue;
		}
	}
	input.value = JSON.stringify( value );
}

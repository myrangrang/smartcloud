/**
* ownCloud - Usage Charts plugin Forked from DjazzLab Storage Charts Plugin
* @author Alan Vallance
// +-----------------------------------------------------------------------+
// | Copyright (C) 2013, ed-237                                            |
// +-----------------------------------------------------------------------+
// | This file is free software; you can redistribute it and/or modify     |
// | it under the terms of the CeCIll v2 as published by three French      |
// | public research organisations, the CEA, the CNRS and INRIA            |
// | This file is distributed in the hope that it will be useful           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of        |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the          |
// | CeCIll License for more details.                                      |
// | http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt              |
// +-----------------------------------------------------------------------+
// | Author: pFa                                                           |
// +-----------------------------------------------------------------------+
*/
// script loaded along with settings.tpl.php (see chartsUsedStorage.js)
function settingsGetInput() {
//       ----------------
	return {chk:[$('#cpie_rfsus_e').is(':checked'),$('#clines_usse_e').is(':checked'),$('#chisto_us_e').is(':checked')]};
} // settingsGetInput

function settingsCheckInput() {
//       ------------------
	var	myInput = settingsGetInput();

	if (	( OC_DLSC.set.chk[0] == myInput.chk[0] )
	&&	( OC_DLSC.set.chk[1] == myInput.chk[1] )
	&&	( OC_DLSC.set.chk[2] == myInput.chk[2] ) ) {
//	No change has been made
		$('#settings_xmit')
		.attr('disabled',true)
		.addClass('ui-state-disabled');
	} else if (	( myInput.chk[0] == false )
	&&		( myInput.chk[1] == false )
	&&		( myInput.chk[2] == false ) ) {
//	At least one graph must be selected
		$('#settings_xmit')
		.attr('disabled',true)
		.addClass('ui-state-disabled');
	} else {
//	User changed settings and at least on graph is selected
		$('#settings_xmit')
		.removeAttr('disabled')
		.removeClass('ui-state-disabled');
	}
} // settingsCheckInput

// 1) Store the current input values
	OC_DLSC.set = settingsGetInput();
// 2) Disable the buttons (Save and Cancel)
	settingsCheckInput();
// 3) Check changes on input
	$('#cpie_rfsus_e').change( function () {
		settingsCheckInput();
	});
	$('#clines_usse_e').change( function () {
		settingsCheckInput();
	});
	$('#chisto_us_e').change( function () {
		settingsCheckInput();
	});
// 4) Save changes (and reload charts)
	$('#usage_charts').submit(function (e) {
		$('#appsettings_popup').hide();
	});

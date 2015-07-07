/**
* @package menu_on_top
* @category base
* @author m6n,Awikatchikaen
* @copyright 2012-2013 m6n,Awikatchikaen <lord.awikatchikaen@gmail.com>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php?content=157091
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the license, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * @file js/menu_on_top.JS
 * @author m6n,Awikatchikaen
 */

/**  
*   {
*   }
*/


/* Under Owncloud files its possible to share something
 * If you click on share a link is displayed
 * the link is not copieable
 * This is because the input is marked as "Readonly"
 * The following code is used to change the input to R/W
*/	

/****************************************************************************
 * jquery.waituntilexists.js - https://gist.github.com/PizzaBrandon/5709010
 * *************************************************************************/
;(function ($, window) {
 
var intervals = {};
var removeListener = function(selector) {
 
	if (intervals[selector]) {
		
		window.clearInterval(intervals[selector]);
		intervals[selector] = null;
	}
};
var found = 'waitUntilExists.found';
 
/**
 * @function
 * @property {object} jQuery plugin which runs handler function once specified
 *           element is inserted into the DOM
 * @param {function|string} handler 
 *            A function to execute at the time when the element is inserted or 
 *            string "remove" to remove the listener from the given selector
 * @param {bool} shouldRunHandlerOnce 
 *            Optional: if true, handler is unbound after its first invocation
 * @example jQuery(selector).waitUntilExists(function);
 */
 
$.fn.waitUntilExists = function(handler, shouldRunHandlerOnce, isChild) {
 
	var selector = this.selector;
	var $this = $(selector);
	var $elements = $this.not(function() { return $(this).data(found); });
	
	if (handler === 'remove') {
		
		// Hijack and remove interval immediately if the code requests
		removeListener(selector);
	}
	else {
 
		// Run the handler on all found elements and mark as found
		$elements.each(handler).data(found, true);
		
		if (shouldRunHandlerOnce && $this.length) {
			
			// Element was found, implying the handler already ran for all 
			// matched elements
			removeListener(selector);
		}
		else if (!isChild) {
			
			// If this is a recurring search or if the target has not yet been 
			// found, create an interval to continue searching for the target
			intervals[selector] = window.setInterval(function () {
				
				$this.waitUntilExists(handler, shouldRunHandlerOnce, true);
			}, 500);
		}
	}
	
	return $this;
};
 
}(jQuery, window));
/****************************************************************************
 * jquery.waituntilexists.js - https://gist.github.com/PizzaBrandon/5709010
 * *************************************************************************/

/* Remove Attribut "Readonly" of Share-Link */
$("#linkText").waitUntilExists(function(){
	$('#linkText').attr('readonly', false);
});





    
/* Get windows width */
var breite = window.innerWidth;

/* Wait page to be loaded */
	$(document).ready(function () {
			
		if (breite<1080){

		
		  /* Implemention of "menu on top" */		    
		  $("#navigation").hide();
		  $("#navigation").css("visibility", "visible");
		  $("#owncloud").click(function(e){
			e.preventDefault();
			$("#navigation").slideToggle(); 
		  });
		  
		  
		  $("#navigation li a").click(function(e){
			$("#navigation").slideUp(); 
		  });

		  /*$("#navigation, #owncloud").mouseleave(function(){
			$("#navigation").slideUp(); 
		  });*/
		  
		  
		}
	});
	
	
	/*
	$(function () {
		//$(".campaign-box span:last").hide();
		$("#owncloud").hover(function () { 
			//$("#navigation").css("opacity", 0.5);
			$("#navigation").fadeIn("fast");           
		}, function () {        
			//$("#navigation").css("opacity", 1); 
			$("#navigation").hide("slow");
		});
	});
	*/

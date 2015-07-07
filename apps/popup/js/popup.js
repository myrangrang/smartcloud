$(document).ready(function() {
	if(/(files)/i.exec(window.location.href)==null) return;
/*	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "positionClass": "toast-top-full-width",
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
//      $.get( "popup/ajax/popup.php", function( data ) {
	};*/
	$.getJSON( OC.filePath('popup', 'ajax', 'options.php'), function( data ) {
	  if (data != "") 
		//var myArray = JSON.parse(data);
		//alert (data.messages.length);
		console.log(data);
		$.each(data.messages,function(){
		if (this.message !="")
			{
			//alert(this.messageType);
			toastr.options = {"closeButton": this.options['closeButton'],
							  "debug": false,
							  "positionClass": this.options['positionClass'],
							  "onclick": null,
							  "showDuration": "300",
							  "hideDuration": "1000",
							  "timeOut": this.options['timeOut'],
							  "extendedTimeOut": "1000",
							  "showEasing": "swing",
							  "hideEasing": "linear",
							  "showMethod": "fadeIn",
							  "hideMethod": "fadeOut"};
			console.log(toastr.options);			  
			toastr[this.messageType](this.message,this.title);
			//alert(this.message);
			}
		});
	});
	//$.get( OC.filePath('popup', 'ajax', 'popup.php'), function( data ) {
	//  if (data != "") toastr.info(  "<div align='center'>" + data +  "</div>" );
	//});	
});

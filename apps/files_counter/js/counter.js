/**
* Copyright (c) 2014 Masoud KHorram <usef62@owncloud.com>
* This file is licensed under the Affero General Public License version 3 or
* later.
* See the COPYING-README file.
*/

$(document).ready(function(){
    if (typeof FileActions !== 'undefined') {
        FileActions.register('all',t('files_counter','Counter'), OC.PERMISSION_READ,
        	function(){
            	return OC.imagePath('files_counter','Brand.png');
        	},
	        function(filename, context){
			var dir = context.dir || context.fileList.getCurrentDirectory();
			var dir = $('#dir').val();
			if(dir != '/'){
				file = dir+"/"+filename;
			}else{
				file = "/"+filename;
			}
			
		var fileid = context.$file.data('id');
		
		var shareowner = context.$file.attr('data-share-owner');
		
		if(typeof shareowner !== "undefined")
		  return false;
		
		var appendTo = $('tr').filterAttr('data-file',filename).find('td.filename');
            	if (($('#counterdropdown').length > 0)) {
            	    if (file != $('#counterdropdown').data('file')) {
            	        OC.Counter.hideDropDown(function () {
            	            $('tr').removeClass('mouseOver');
            	            $('tr').filterAttr('data-file',filename).addClass('mouseOver');
            	            OC.Counter.showDropDown(fileid, appendTo);
            	        });
            	    }
            	} else {
            	    OC.Counter.showDropDown(fileid, appendTo);
            	}
        });
	FileActions.register('all', 'Download', OC.PERMISSION_READ, function () {
	  return OC.imagePath('core', 'actions/download');
	},
		function (filename, context) {
			var dir = context.dir || context.fileList.getCurrentDirectory();
			var dir = $('#dir').val();
			if(dir != '/'){
				file = dir+"/"+filename;
			}else{
				file = "/"+filename;
			}
			var $tr = context.$file;
			var fileid =  $tr.data('id');
			
 			OC.Counter.setCounter(fileid);
			var url = context.fileList.getDownloadUrl(filename, dir);
			if (url) {
				OC.redirect(url);
			}
		});
    	}
    	$(this).click(function(event) {
        	if (!($(event.target).hasClass('counterdropdown')) && $(event.target).parents().index($('#counterdropdown')) == -1) {
        	    if ($('#counterdropdown').is(':visible')) {
        	        OC.Counter.hideDropDown(function() {
        	            $('tr').removeClass('mouseOver');
        	        });
        	    }
        	}
    	});
});

OC.Counter={
    loadCounter:function(fileid) {
        $.ajax({
            type: 'POST',
            url: OC.filePath('files_counter', 'ajax', 'getCounter.php'),
            data: {
                fileid: fileid
            },
            success: function(data) {
	      if(data !== '')
	      {
                $('div#dwcount').text("Downloads : " + '' + data);
	      } else {
		$('div#dwcount').text("Downloads : " + '' + '0');
	      }
            }
        });
	
	$.ajax({
            type: 'POST',
            url: OC.filePath('files_counter', 'ajax', 'getIPDate.php'),
            data: {
                fileid: fileid
            },
            success: function(data) {
	      $('#selectadd').html(data);
            }
        });
	
    },
    setCounter :function(fileid) {
	 $.ajax({
            type: 'POST',
            url: OC.filePath('files_counter', 'ajax', 'setCounter.php'),
            data: {
                fileid: fileid
            },
	    async:false,
	    success:function(data){
		if(data.status=='success'){
		}
	    }
        });
	 
    },
    showDropDown:function(fileid, appendTo) {
      OC.Counter.loadCounter(fileid);
        var html = '<div id="counterdropdown" class="counterdropdown" data-item="'+fileid+'">';
        html += '<div id="dwcount"></div>';
	html += '<select id="selectadd"></select>';
        html += '</div>';
        $(html).appendTo(appendTo);
    },
    hideDropDown:function(callback) {
        $('#counterdropdown').hide('blind', function() {
            $('#counterdropdown').remove();
            if (callback) {
                callback.call();
            }
        });
    }
};
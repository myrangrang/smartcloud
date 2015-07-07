(function($){
  var virustotal = {
    init: function() {
      if (typeof FileActions !== 'undefined') {
        FileActions.register(
          'file',
          t('virustotal','virustotal'),
          OC.PERMISSION_READ,
          OC.imagePath('core','actions/info'),
          virustotal.check
        );
      };
    },
    check: function(file) {
      dom = this.elem.find('.action[data-action=virustotal]');
      if(!dom.hasClass('vt-check')) {
        dom.html(virustotal.load);
        dom.addClass('virustotal-check');
        virustotal.ajax(file);
      } else {
        alert(dom.html());
      }
    },
    load: 'Searching file on VT <img src="'+OC.imagePath('core','loading.gif')+'">',
    ajax: function(file) {
      var data = {source: file, dir: $('#dir').val()+'/'};
      $.ajax({
      type: 'GET',
      url: OC.filePath('virustotal', 'ajax', 'virustotal.php'),
      dataType: 'json',
      data: data,
      async: false,
      success: function(info) {
        dom = $('.virustotal-check').first();
        dom.text('VT: '+info.data[0]);
        dom.addClass('vt-check');
        dom.removeClass('virustotal-check');
      }
    });
    }
  }
  $(document).ready(virustotal.init);
})(jQuery)

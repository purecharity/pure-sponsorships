(function( $ ) {
  'use strict';

  jQuery('#upload_image_button').click(function() {
    formfield = jQuery('#branding_image').attr('name');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
  });
   
  window.send_to_editor = function(html) {
    imgurl = jQuery('img',html).attr('src');
    jQuery('#puresponsorships_after_sponsor_content').val(imgurl);
    tb_remove();
  }
 
});
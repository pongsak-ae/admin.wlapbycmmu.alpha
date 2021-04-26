$(document).ready(function(){
  $("#add_banner_img").on('change', function(){
      var file = this.files[0];
      var reader = new FileReader();
      reader.onloadend = function () {
          $('img#banner_img').attr('src', reader.result);
      }
      if (file) {
          reader.readAsDataURL(file);
      } else {
          return false;
      }
  });

});
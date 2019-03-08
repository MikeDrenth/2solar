$(document).ready(function() {

    // Function: Click on info button needs to show the extra info div
    if ($('.upload-btn-wrapper .infoIcon').length > 0) {
        $('.upload-btn-wrapper .infoIcon').on('click', function() {
            var extraInfo = $(this).parent().find('.extraInfo');
            var infoIcon  = $(this).parent().find('.infoIcon i');
            // If it has the info button, remove it and add the close icon
            if($(infoIcon).hasClass('fa-info-circle')) {
                $(infoIcon).removeClass('fa-info-circle');
                $(infoIcon).addClass('fa-times-circle');
            // If icon is the close button, remove it and add the info button again
            } else if ($(infoIcon).hasClass('fa-times-circle')) {
                $(infoIcon).addClass('fa-info-circle');
                $(infoIcon).removeClass('fa-times-circle'); 
            }
            // Show or hide the extra info content
            $(extraInfo).toggleClass('active');
        });
    }

    // Function: Check if field changes, valid file type and file extension
    if ($('.fileUpload').length > 0) {
        $('.fileUpload').on('change', function() {
            var fileExtension   = ['application/pdf', 'image/jpeg', 'image/svg+xml', 'image/png'];
            var uploadedSize    = this.files[0].size;
            var uploadedType    = this.files[0].type;
            var uploadValid     = false;

            // Check if the file extension is allowed
            if ($.inArray(uploadedType, fileExtension) < 0) {
                alert('Upload aub een ander document');
                var uploadValid = false;
            } else {
                var uploadValid = true;
            }  
            
            // Check the file size
            if(uploadedSize < 100000) {
                var uploadValid = true;
            } else {
                alert('Het bestand is te groot, upload aub een kleiner bestand');
                var uploadValid = false;
            }

            // File is valid, add class to show it succeed
            if(uploadValid) {
                $(this).parent().addClass('uploadValid');
            }
        });
    }

    // Function: Start progress bar animation
    $(".bar-con .bar").progress();
    
});

// Function: Get percentage and add this as style with
(function($) {
    $.fn.progress = function() {
      var percent = this.data("percent");
      this.css("width", percent+"%");
    };
}(jQuery));
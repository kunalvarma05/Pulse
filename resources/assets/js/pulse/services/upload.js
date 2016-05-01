//Check if the dropzone element is available
  if($("#campaign-gallery").length){

    var campaignGallery = new Dropzone("#campaign-gallery", {
      paramName: "file",
      maxFilesize: 50,
      parallelUploads: 20,
      createImageThumbnails: true,
      thumbnailWidth: 300,
      thumbnailHeight: 300,
      previewsContainer: "#gallery-preview-container",
      previewTemplate: jQuery('.gallery-preview-template').html(),
      maxFiles: null,
      uploadMultiple: false,
      autoProcessQueue: false,
      addRemoveLinks: false,
      clickable: true,
      acceptedFiles: "image/*",
      headers: {
        'X-CSRF-Token' : jQuery('meta[name=_token]').attr('content')
      }
    });

    campaignGallery.on("processing", function(file) {
      $(file.previewElement).find('.progress').addClass('active');
    });

    campaignGallery.on("addedfile", function(file) {
      $("#gallery-preview-box").fadeIn(400);
    });

    campaignGallery.on("removedfile", function(file) {
      if(campaignGallery.getQueuedFiles().length < 1){
        $("#gallery-preview-box").fadeOut(400);
      }
    });

    campaignGallery.on("success", function(file, data) {
      $(".gallery-item-box").find(".empty-state").fadeOut(400);
      var html = $("#gallery-item-template").inject(data.message);
      $(file.previewElement).fadeOut(400).remove();
      $("#campaign-gallery-box").append(html);
    });

    campaignGallery.on("error", function(file, data, xhr) {
      $(file.previewElement).find("[data-errormessage=true]").addClass('in').text(data.message ? data.message : "Something went wrong!" );
      $(file.previewElement).find('.progress-bar').css("width","0%");
      file.status = Dropzone.QUEUED;
    });

    campaignGallery.on("complete", function(file) {
    //If there are files still remaining in the queue
    if(campaignGallery.getQueuedFiles().length < 1){
      $("#gallery-preview-box").fadeOut(400);
    }
    $(file.previewElement).find('.progress').removeClass('active');
    $("#campaign-gallery-upload").removeClass('btn-loading loading');
  });

    $("#campaign-gallery-upload").click(function(){
      $(this).addClass("btn-loading loading");
      campaignGallery.processQueue();
    });

  }
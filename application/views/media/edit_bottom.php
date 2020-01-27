



<script>



  // JavaScript for disabling form submissions if there are invalid fields
  (function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                  $("#errors").text("<?= _('Hay campos con errores. Verifique todas las solapas.')  ?>").show();
                  event.preventDefault();
                  event.stopPropagation();
              }
              else {
                  // valido correctamente, mostrar waiting things.
                  $("#errors").hide();
                  $(".loading").show();
                  $("#submitButton").removeClass("btn-primary").addClass("btn-warning").prop("disabled", true);
              }
              form.classList.add('was-validated');
          }, false);
        });
    }, false);
  })();

 $('.custom-file-input').on('change', function() {
     var fname = $('[name=file_media_src]').val().split('\\').pop();
     $('#file_name').val(fname);
  });

  $('#src_type').on('change', function() {
     var src_type = $(this).val();
     if ( src_type == 'FILE' ) {
        $('a#upload-tab').text('<?= _("Upload") ?>');
        $('#upload_section').show();
        // unset link required
        $('#link_media_src').removeAttr("required");
        $('#link_section').hide();
     }
     else {
        $('a#upload-tab').text('<?= _("Link") ?>');
        $('#upload_section').hide();
         // set link required and show
        $('#link_media_src').attr("required","required");
        $('#link_section').show();
     }
  });

</script>

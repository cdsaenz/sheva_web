
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
                  event.preventDefault();
                  event.stopPropagation();
              }
              else {
                  // valido correctamente, mostrar waiting things.
                  $(".loading").show();
                  $("#submitButton").removeClass("btn-primary").addClass("btn-warning").prop("disabled", true);
              }
              form.classList.add('was-validated');
          }, false);
          });
      }, false);
    })();


    $(document).ready(function(){
        // hook for socio referencia
        hook_references();
    });

    function hook_references()
    {
          var data = <?= $this->app->json_get_suppliers($cid) ?>;
          $('#supp_name').autocomplete({
              /* options */
              source: data,
              minLength: 2,
              change: function(event, ui) {
                  var name_id = $(this).attr("id");
                  var value_id = $(this).attr("data-id");
                  /* null when not in list! bingo.*/
                  if (ui.item == null) {
                      $("#" + name_id).val("");
                      $( "[name=" + value_id +  "]" ).val( 0 );
                      $("#" + name_id).focus();
                  }
              },
              select: function( event, ui ) {
                  var label = ui.item.label;
                  var value = ui.item.value;
                  var name_id = $(this).attr("id");
                  var value_id = $(this).attr("data-id");
                  $( "#" + name_id ).val( label );
                  $( "[name="+ value_id +"]" ).val( value );
                  return false;
              }
          });
    };




</script>

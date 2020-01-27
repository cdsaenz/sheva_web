
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
                $(".loading").show();
                $("#submitButton").removeClass("btn-primary").addClass("btn-warning").prop("disabled", true);
            }
            form.classList.add('was-validated');
        }, false);
        });
    }, false);
  })();

</script>

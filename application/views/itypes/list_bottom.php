<script>
    /* prevent refresh/back issue*/
    /* issue now is that you'll lose the filters*/
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    // launch the pdf conversion routine.
    // UNUSED FOR NOW
    $('#topdf_disabled').click(function(){
       $.ajax({  url: '<?= base_url("items/topdf") ?>',
                type: 'post',
                cache: false,
                data: {title: '<?= $title ?>',
                       html : $('#list').html() }
              })
             .done(function(data) {
                console.log(data + ' ok ');
             })
             .fail(function() {
                console.log("error");
             });
    });

    $("#resetFilter").click(function(){
       $("#keyword").val("");
       $("#type").prop("selectedIndex", 0);
       $("#searchForm").submit();
    });

</script>


    <script>
        /* prevent refresh/back issue*/
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }


        // launch the pdf conversion routine.
        // UNUSED FOR NOW
        $('#topdf_disabled').click(function(){
           $.ajax({  url: '<?= base_url("mycustomers/topdf") ?>',
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
           $("#searchForm").submit();
        });

    </script>

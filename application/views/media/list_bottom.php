
    <script>
        /* prevent refresh/back issue*/
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

        $("#resetFilter").click(function(){
           $("#keyword").val("");
           $("#searchForm").submit();
        });
    </script>

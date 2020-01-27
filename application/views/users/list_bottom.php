

<script>
    $("#resetFilter").click(function(){
       $("#keyword").val("");
       $("#type").prop("selectedIndex", 0);
       $("#searchForm").submit();
    });
</script>

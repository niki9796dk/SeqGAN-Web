+function ($) {
    $(document).ready(function () {
        let searchParams = new URLSearchParams(window.location.search)

        // Basic
        $("table.dataTable").DataTable();

        // Specific - index.php
        $('#experiments_table').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/api/datatables/experiments.php",
                "data": {
                    "period": searchParams.get("period"),
                }
            },
            "order": [[ 0, "desc" ]],
            "createdRow": function (row, data, index) {
                $(row).addClass('clickable');
                $(row).data("href", "/views/pages/experiment.php?id="+data[0]);
            },
        } );

        // Specific - experiment.php
        $('#experiment_table').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/api/datatables/experiment.php",
                "data": {
                    "experiment_id": searchParams.get("id"),
                }
            },
            "order": [[ 0, "desc" ]],
            "createdRow": function (row, data, index) {
                $($(row).children()[1]).addClass("w-75");
            },
        } );


    });
}(jQuery);

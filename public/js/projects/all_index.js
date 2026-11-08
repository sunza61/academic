$(function () {
    // Initialize only if table exists and does NOT contain the empty state row
    if ($("#projectTable").length) {
        var emptyRow = $("#projectTable tbody tr .text-muted");
        
        if (emptyRow.length === 0) {
            $("#projectTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "destroy": true
            });
        }
    }
});

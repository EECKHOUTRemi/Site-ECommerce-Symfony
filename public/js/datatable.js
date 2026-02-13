$(document).ready(function () {
    $('#users').DataTable({
        layout: {
            top1: 'searchBuilder'
        },
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        info: true,
        responsive: true
    });
});
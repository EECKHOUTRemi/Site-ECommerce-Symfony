$(document).ready(function () {
    $('#users').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        info: true,
        responsive: true
    });
});
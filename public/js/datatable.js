$(document).ready(function () {
    $('#users').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        info: true,
        responsive: true
    });
    
    $('#orders').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        info: true,
        responsive: true
    });
    
    $('#racquets').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        info: true,
        responsive: true
    });

    $('#userOrders').DataTable({
        ordering: true,
        paging: false,
        searching: false,
        info: false,
        scrollY: '400px',
        scrollCollapse: true
    });
    
    $('#userRatings').DataTable({
        ordering: true,
        paging: false,
        searching: false,
        info: false,
        scrollY: '400px',
        scrollCollapse: true
    });
    
});
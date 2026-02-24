$(document).ready(function () {
    $('#users, #orders, #racquets, #promoCodes').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        info: true,
        responsive: true
    });

    $('#userOrders, #userRatings').DataTable({
        ordering: true,
        paging: false,
        searching: false,
        info: false,
        scrollY: '400px',
        scrollCollapse: true
    });
});
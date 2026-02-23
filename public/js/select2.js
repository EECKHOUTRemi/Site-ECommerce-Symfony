$(document).ready(function() {
    $('.search-racquet').select2({
        width: '100%',
        placeholder: 'Search for a racquet...',
        minimumInputLength: 2,
        ajax: {
            url: 'http://localhost/e-commerce-raquettes/public/racquets/data',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                return {
                    results: data.racquets.map(function(racquet) {
                        return {
                            id: racquet.id,
                            text: racquet.brand + ' ' + racquet.model + ' - $' + racquet.price
                        };
                    })
                };
            },
            cache: true
        }
    });
});
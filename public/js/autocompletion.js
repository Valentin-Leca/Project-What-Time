(function() {
    var placesAutocomplete = places({
        appId: 'pl7DB0Z2S0I9',
        apiKey: '23400a6abe5dcca5d7a6819cb6b08147',
        container: document.querySelector('#address')
    });

    placesAutocomplete.on('clear', function() {
        $address.textContent = 'none';
    });

})();
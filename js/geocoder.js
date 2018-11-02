function reverseGeocode(location, callback){
  new google.maps.Geocoder().geocode( { 'location': location}, function(results, status) {
    if (status === 'OK') {
      if (results[0]) {
        callback(results[0].formatted_address);
      }
    }
    else {
      callback(0);
    }
  });
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing Form</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&libraries=places" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let map;
        let marker;
        let latitude = 0;
        let longitude = 0;

        function initMap() {
            const center = { lat: 33.8688, lng: 35.4955 }; // Beirut, Lebanon
            
            map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: 12,
            });

            marker = new google.maps.Marker({
                position: center,
                map: map,
                draggable: true,
            });

            google.maps.event.addListener(marker, "dragend", function(event) {
                latitude = event.latLng.lat();
                longitude = event.latLng.lng();
                document.getElementById("latitude").value = latitude;
                document.getElementById("longitude").value = longitude;
            });

            const input = document.getElementById("location");
            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener("place_changed", function() {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    latitude = place.geometry.location.lat();
                    longitude = place.geometry.location.lng();
                    marker.setPosition(place.geometry.location);
                    map.setCenter(place.geometry.location);
                    document.getElementById("latitude").value = latitude;
                    document.getElementById("longitude").value = longitude;
                }
            });
        }

        window.onload = initMap;
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-7xl mx-auto py-12 px-6">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Property Listing Form</h2>
            <form action="/your-endpoint" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Form Column -->
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" required></textarea>
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" id="location" name="location" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="text" id="latitude" name="latitude" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" readonly>
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="text" id="longitude" name="longitude" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" readonly>
                            </div>
                        </div>
                        <div>
                            <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price Per Night</label>
                            <input type="number" id="price_per_night" name="price_per_night" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" required>
                        </div>
                        <div>
                            <label for="cleaning_fee" class="block text-sm font-medium text-gray-700">Cleaning Fee</label>
                            <input type="number" id="cleaning_fee" name="cleaning_fee" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" required>
                        </div>
                        <div>
                            <label for="security_deposit" class="block text-sm font-medium text-gray-700">Security Deposit</label>
                            <input type="number" id="security_deposit" name="security_deposit" class="mt-1 block w-full border border-gray-300 rounded-lg p-2" required>
                        </div>
                        <div>
                            <label for="cancellation_policy" class="block text-sm font-medium text-gray-700">Cancellation Policy</label>
                            <textarea id="cancellation_policy" name="cancellation_policy" rows="4" class="mt-1 block w-full border border-gray-300 rounded-lg p-2"></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" class="h-5 w-5 text-indigo-600 border-gray-300 rounded" checked>
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active Listing</label>
                        </div>
                    </div>

                    <!-- Right Map Column -->
                    <div>
                        <div id="map" class="h-96 w-full rounded-lg shadow-md border border-gray-300"></div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300">Submit Listing</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

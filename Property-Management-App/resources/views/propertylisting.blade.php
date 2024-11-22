<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing Form</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{}}&libraries=places" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let map;
        let marker;
        let latitude = 0;
        let longitude = 0;

        function initMap() {
            const center = { lat: 33.8688, lng: 35.4955 };
            
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
<body class="bg-gray-50 font-sans">
    <div class="max-w-7xl mx-auto py-12 px-6">
        <div class="bg-white p-10 rounded-xl shadow-lg border border-gray-200 space-y-8">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">Property Listing</h2>
            <form action="{{route('list.property')}}" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                 
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Property Title</label>
                            <input type="text" id="title" name="title" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required></textarea>
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" id="location" name="location" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="text" id="latitude" name="latitude" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" readonly>
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="text" id="longitude" name="longitude" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" readonly>
                            </div>
                        </div>
                        
                        <div>
                            <label for="cleaning_fee" class="block text-sm font-medium text-gray-700">Cleaning Fee</label>
                            <input type="number" id="cleaning_fee" name="cleaning_fee" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
                        </div>
                        <div>
                            <label for="security_deposit" class="block text-sm font-medium text-gray-700">Security Deposit</label>
                            <input type="number" id="security_deposit" name="security_deposit" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
                        </div>
                        <div>
                            <label for="cancellation_policy" class="block text-sm font-medium text-gray-700">Cancellation Policy</label>
                            <textarea id="cancellation_policy" name="cancellation_policy" rows="4" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300"></textarea>
                        </div>
                        
                       
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">Amenities</label>
                            <div class="grid grid-cols-2 gap-4">
                                
                                <div>
                                    <input type="checkbox" id="wifi" name="amenities[]" value="WiFi" class="h-5 w-5 text-indigo-600 border-gray-300 rounded">
                                    <label for="wifi" class="ml-2 text-sm text-gray-700">WiFi</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="pool" name="amenities[]" value="Pool" class="h-5 w-5 text-indigo-600 border-gray-300 rounded">
                                    <label for="pool" class="ml-2 text-sm text-gray-700">Pool</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="parking" name="amenities[]" value="Parking" class="h-5 w-5 text-indigo-600 border-gray-300 rounded">
                                    <label for="parking" class="ml-2 text-sm text-gray-700">Parking</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="gym" name="amenities[]" value="Gym" class="h-5 w-5 text-indigo-600 border-gray-300 rounded">
                                    <label for="gym" class="ml-2 text-sm text-gray-700">Gym</label>
                                </div>
                            </div>
                        </div>
                        
                       
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" id="price" name="price" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
                            </div>
                        </div>

                        
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-4">Upload Photo</label>
                            <div id="image-preview-container" class="flex gap-4">
       
        <div class="w-24 h-24 bg-gray-100 flex items-center justify-center rounded-xl cursor-pointer shadow-md border border-gray-300" id="image-upload-btn">
            <span class="text-3xl text-gray-700">+</span>
        </div>
    </div>
                           
                        </div>

                        
                        <div class="flex items-center space-x-3">
    <input type="checkbox" id="is_active" name="is_active" class="h-5 w-5 text-indigo-600 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 transition duration-300">
    <label for="is_active" class="text-sm font-medium text-gray-700">Active Listing</label>
</div>

                    </div>
                    <div class="space-y-6">
  
    <div class="space-y-6">

    <div class="lg:h-[450px]">
        <div id="map" class="h-full w-full rounded-xl shadow-lg border border-gray-300"></div>
    </div>

  
    <label class="block text-lg font-medium text-gray-700 mb-4">Image Preview</label>
    <div id="image-preview-container-map" class="overflow-y-auto flex flex-col items-center gap-4 bg-gray-100 p-4 rounded-xl shadow-lg border border-gray-300 max-h-[798px]">
    
</div>

</div>
<textarea  id="imagearray" name="imagearray" rows="4" class="hidden"></textarea>

                </div>

                <script>
const imagePreviewContainer = document.getElementById('image-preview-container');
const imageUploadBtn = document.getElementById('image-upload-btn');
const imagePreviewContainerMap = document.getElementById('image-preview-container-map');

const uploadedImages = [];

imageUploadBtn.addEventListener('click', function () {
    createImageInput(); 
});
let imageCount = 0;
const MAX_IMAGES = 3;
function createImageInput() {

    if (imageCount >= MAX_IMAGES) {
        showAlert(`You can only upload up to ${MAX_IMAGES} images.`);
        return;
    }


    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.name = 'photo[]';
    input.classList.add('hidden'); 

    input.addEventListener('change', function () {
        const file = input.files[0];
        if (file) {
            imageCount++;
            const fileName = file.name; 
            uploadedImages.push(fileName);
            document.getElementById('imagearray').innerHTML = uploadedImages.join(', ');

            console.log(uploadedImages); 
            const reader = new FileReader();
            reader.onload = function (e) {
                const uniqueId = `image-${Date.now()}`;

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;
                img.className = "w-full h-full object-cover rounded-xl"; 

                const img1 = document.createElement('img');
                
                img1.src = e.target.result;
                img1.alt = file.name;
                img1.className = "w-full h-full object-cover rounded-xl";
                img1.id = `${uniqueId}-map`;

                const hoverOverlay = document.createElement('div');
                hoverOverlay.className = `
                    absolute inset-0 bg-red-500 bg-opacity-50 flex items-center justify-center
                    opacity-0 group-hover:opacity-100 transition-opacity duration-300
                `;


                const imagePreview = document.createElement('div');
                imagePreview.id = uniqueId;
                imagePreview.classList.add(
                    'relative',
                    'w-24',
                    'h-24',
                    'bg-gray-100',
                    'rounded-xl',
                    'overflow-hidden',
                    'shadow-md',
                    'border',
                    'border-gray-300',
                    'group'
                );
                imagePreview.appendChild(img);

                
                imagePreviewContainer.appendChild(imagePreview); 
             
                imagePreviewContainerMap.appendChild(img1); 

                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'X';
                removeBtn.className = 'text-white text-3xl font-bold';
                removeBtn.addEventListener('click', () => {
                    imagePreview.remove();
                    const index = uploadedImages.indexOf(fileName);
                    if (index > -1) {
                        uploadedImages.splice(index, 1); 
                    }
                    console.log(uploadedImages);
                    document.getElementById('imagearray').innerHTML = uploadedImages.join(', ');

                    document.getElementById(`${uniqueId}-map`).remove();
                    imageCount--; 
                });
               
                hoverOverlay.appendChild(removeBtn);
                imagePreview.appendChild(hoverOverlay);
            };

            reader.readAsDataURL(file);
        }
    });

    input.click(); 
}

function showAlert(message) {
    const alertDiv = document.createElement('div');
    alertDiv.textContent = message;

    alertDiv.className = `
        fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2
        bg-red-600 text-white text-sm rounded-md shadow-lg
        transition-opacity duration-300 opacity-0
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.classList.remove('opacity-0'); 
        alertDiv.classList.add('opacity-100');
    }, 50);

    setTimeout(() => {
        alertDiv.classList.remove('opacity-100'); 
        alertDiv.classList.add('opacity-0');
    }, 3000);

    setTimeout(() => {
        alertDiv.remove();
    }, 3300);
}


</script>


                <div class="mt-8 text-left">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300">Submit Listing</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


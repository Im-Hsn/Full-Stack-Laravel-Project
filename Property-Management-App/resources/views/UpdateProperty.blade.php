@extends('layouts.app')

@section('title', 'Property Listing Form')

@push('scripts')
<script>
    let map, marker, latitude = 0, longitude = 0;

    function initMap() {
        const center = { lat: parseFloat({{ $property->latitude ?? 0 }}), 
        lng: parseFloat({{ $property->longitude ?? 0 }})  };

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

        autocomplete.addListener("place_changed", function () {
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
@endpush

@section('content')


    <div class="max-w-7xl mx-auto py-12 px-6">
        <div class="bg-white p-10 rounded-xl shadow-lg border border-gray-200 space-y-8">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">Property Listing</h2>
            <form  action="{{route('update.property',$property->id)}}" method="POST" enctype="multipart/form-data">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            @csrf
            @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                 
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Property Title</label>
                            <input type="text" id="title" name="title" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->title}}" required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>{{$property->description}}</textarea>
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" id="location" name="location" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->location}}" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="text" id="latitude" name="latitude" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->latitude}}" readonly>
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="text" id="longitude" name="longitude" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->longitude}}" readonly>
                            </div>
                        </div>
                        
                        <div>
                            <label for="cleaning_fee" class="block text-sm font-medium text-gray-700">Cleaning Fee</label>
                            <input type="number" id="cleaning_fee" name="cleaning_fee" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->cleaning_fee}}" required>
                        </div>
                        <div>
                            <label for="security_deposit" class="block text-sm font-medium text-gray-700">Security Deposit</label>
                            <input type="number" id="security_deposit" name="security_deposit" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->security_deposit}}" required>
                        </div>
                        <div>
                            <label for="cancellation_policy" class="block text-sm font-medium text-gray-700">Cancellation Policy</label>
                            <textarea id="cancellation_policy" name="cancellation_policy" rows="4" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">{{$property->cancellation_policy}}</textarea>
                        </div>
                        
                       
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">Amenities</label>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($amenities as $amenity )
                                <div>
                                    <input type="checkbox" id="{{$amenity->amenity}}" name="amenities[]" value="{{$amenity->amenity}}" class="h-5 w-5 text-indigo-600 border-gray-300 rounded" @if($propertyamenities->contains('id', $amenity->id)) checked @endif>
                                    <label for="wifi" class="ml-2 text-sm text-gray-700">{{$amenity->amenity}}</label>
                                </div>
                                @endforeach
                           
                            </div>
                        </div>
                        
                       
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" id="price" name="price" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->price_per_night-$property->security_deposit-$property->cleaning_fee}}" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->start_date}}" required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="mt-2 block w-full border border-gray-300 rounded-xl p-3 text-gray-800 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" value="{{$property->end_date}}" required>
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
    <input type="checkbox" id="is_active" name="is_active" class="h-5 w-5 text-indigo-600 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 transition duration-300" @if($property->is_available==1) checked @endif>
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

@push('scripts')
<script>
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imageUploadBtn = document.getElementById('image-upload-btn');
    const imagePreviewContainerMap = document.getElementById('image-preview-container-map');
    let imageCounter = 0;
    const uploadedImages = [];
    const fileArray = @json($fileArray); // Pass the image array from the backend

    // Initial population of images from the backend
    fileArray.forEach(fileName => {
        const imagePath = "{{ asset('assets') }}/" + fileName;

        // Create image preview
        const uniqueId = `image-${imageCounter++}`;

        const img = document.createElement('img');
        img.src = imagePath;
        img.alt = fileName;
        img.className = "w-full h-full object-cover rounded-xl"; // Styling for the image

        const img1 = document.createElement('img');
        img1.src = imagePath;
        img1.alt = fileName;
        img1.className = "w-full h-full object-cover rounded-xl";
        img1.id = `${uniqueId}-map`;

        // Hover overlay with remove button
        const hoverOverlay = document.createElement('div');
        hoverOverlay.className = `absolute inset-0 bg-red-500 bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300`;

        const imagePreview = document.createElement('div');
        imagePreview.id = uniqueId;
        imagePreview.classList.add(
            'relative', 'w-24', 'h-24', 'bg-gray-100', 'rounded-xl', 'overflow-hidden', 'shadow-md', 'border', 'border-gray-300', 'group'
        );
        imagePreview.appendChild(img);
        imagePreviewContainer.appendChild(imagePreview); // Append the preview to the container

        // Append map image to the map container
        imagePreviewContainerMap.appendChild(img1);

        // Create the remove button
        const removeBtn = document.createElement('button');
        removeBtn.textContent = 'X';
        removeBtn.className = 'text-white text-3xl font-bold';
        removeBtn.addEventListener('click', () => {
            imagePreview.remove();
            const index = uploadedImages.indexOf(fileName);
            if (index > -1) {
                uploadedImages.splice(index, 1);
            }
            document.getElementById('imagearray').innerHTML = uploadedImages.join(', ');

            document.getElementById(`${uniqueId}-map`).remove();
            deleteImage(fileName); // Call delete function on backend
            imageCount--;  // Decrement the image count when an image is removed
        });

        hoverOverlay.appendChild(removeBtn);
        imagePreview.appendChild(hoverOverlay);

        // Add image name to the uploadedImages array
        uploadedImages.push(fileName);
        document.getElementById('imagearray').innerHTML = uploadedImages.join(', ');
    });

    let imageCount = fileArray.length; // Set initial image count to the number of existing images
    const MAX_IMAGES = 3;

    imageUploadBtn.addEventListener('click', function () {
        createImageInput();
    });

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
                imageCount++;  // Increment the image count when a new image is selected
                const fileName = file.name;
                uploadedImages.push(fileName);
                document.getElementById('imagearray').innerHTML = uploadedImages.join(', ');
                uploadImage(file);

                const reader = new FileReader();
                reader.onload = function (e) {
                    const uniqueId = `image-${Date.now()}`;

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    img.className = "w-full h-full object-cover rounded-xl"; // Styling for the image

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
    // Find the image preview container by its unique ID
    const imagePreviewElement = document.getElementById(uniqueId);
    const imageMapElement = document.getElementById(`${uniqueId}-map`);

    // Remove the preview and map image only if they exist
    if (imagePreviewElement) {
        imagePreviewElement.remove();
    }

    if (imageMapElement) {
        imageMapElement.remove();
    }

    // Remove the file from the uploaded images array
    const index = uploadedImages.indexOf(fileName);
    if (index > -1) {
        uploadedImages.splice(index, 1);
    }

    // Update the displayed list of image names
    document.getElementById('imagearray').innerHTML = uploadedImages.join(', ');

    // Decrement the image count as one image was removed
    imageCount--;

    // Call the backend to delete the image from the server
    deleteImage(fileName);
});

                    hoverOverlay.appendChild(removeBtn);
                    imagePreview.appendChild(hoverOverlay);
                };

                reader.readAsDataURL(file);
            }
        });

        input.click();
    }

    function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);

        return fetch('/upload-image', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Image added!');
                } else {
                    throw new Error('Image upload failed');
                }
            });
    }

    function deleteImage(fileName) {
        fetch('/delete-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ fileName }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Image deleted:', fileName);
                } else {
                    console.error('Error deleting image:', data.message);
                }
            })
            .catch(error => console.error('Error deleting image:', error));
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



@endpush



                <div class="mt-8 text-left">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300">Submit Listing</button>
                </div>
            </form>
        </div>
    </div>
@endsection


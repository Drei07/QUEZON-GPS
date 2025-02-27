<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Data with Google Maps</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 90%;
            max-width: 500px;
        }
        h2 {
            color: #333;
        }
        .data {
            font-size: 18px;
            margin: 10px 0;
        }
        .status {
            font-weight: bold;
            color: green;
        }
        .error {
            color: red;
        }
        .refresh {
            margin-top: 15px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .refresh:hover {
            background: #0056b3;
        }
        #map {
            height: 300px;
            width: 100%;
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWrXtUyyqoWHwLddsIRgZKjKc9YGeW7FI"></script> <!-- Replace with your Google Maps API key -->
</head>
<body>

    <div class="container">
        <h2>GPS Data</h2>
        <p class="data">Device Status: <span id="wifi_status" class="status">Loading...</span></p>
        <p class="data">Coordinates: <span id="gpsCoordinates">Loading...</span></p>
        <p class="data">Latitude: <span id="latitude">Loading...</span></p>
        <p class="data">Longitude: <span id="longitude">Loading...</span></p>
        <p class="data">Speed: <span id="speed">Loading...</span> km/h</p>
        <p class="data">Satellites: <span id="satellites">Loading...</span></p>
        <button class="refresh" onclick="fetchData()">Refresh</button>
        <div id="map"></div>
    </div>

    <script>
    let map, marker;

    function initMap(latitude = 14.5995, longitude = 120.9842) {
        // Default location (Manila, Philippines)
        const location = { lat: parseFloat(latitude), lng: parseFloat(longitude) };

        // Initialize the map
        map = new google.maps.Map(document.getElementById('map'), {
            center: location,
            zoom: 15
        });

        // Add a customizable marker
        marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "Current GPS Location",
            icon: {
                url: 'src/img/boat-icon.png',
                scaledSize: new google.maps.Size(90, 90),
            },
            animation: google.maps.Animation.DROP
        });
    }

    function fetchData() {
        fetch('controller.php') // Replace with your PHP file
            .then(response => response.json())
            .then(data => {
                document.getElementById('wifi_status').textContent = data.wifi_status || 'Unknown';
                document.getElementById('gpsCoordinates').textContent = data.gpsCoordinates || 'No Data';
                document.getElementById('latitude').textContent = data.latitude || 'N/A';
                document.getElementById('longitude').textContent = data.longitude || 'N/A';
                document.getElementById('speed').textContent = data.speed || 'N/A';
                document.getElementById('satellites').textContent = data.satellites || 'N/A';

                if (data.latitude && data.longitude) {
                    updateMap(parseFloat(data.latitude), parseFloat(data.longitude));
                }
            })
            .catch(error => {
                document.getElementById('wifi_status').textContent = "Error fetching data";
                document.getElementById('wifi_status').classList.add("error");
                console.error("Error:", error);
            });
    }

    function updateMap(latitude, longitude) {
        const location = { lat: latitude, lng: longitude };

        // Move the map center
        map.setCenter(location);

        // Update the marker position with bounce effect
        marker.setPosition(location);
        marker.setAnimation(google.maps.Animation.BOUNCE);
        
        // Stop bouncing after 2 seconds
        setTimeout(() => {
            marker.setAnimation(null);
        }, 2000);
    }

    // Load the map when the page loads
    window.onload = function () {
        initMap();
        fetchData();
    };

    // Auto-refresh every 10 seconds
    setInterval(fetchData, 10000);
</script>


</body>
</html>

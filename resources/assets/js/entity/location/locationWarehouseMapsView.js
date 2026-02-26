import { switchLang } from '../../grid/components/switch-lang.js';
import { getLocalizedText } from '../../localization/location/getLocalizedText.js';
import { getCoordinatesFromInput } from './utils/getCoordinatesFromInput.js';

var startLocation = [49.820434685575165, 24.003130383455048];
let language = switchLang();
let marker = null;

if ($('#map').length) {
    // Тепер замість координат можна передати URL
    let inputUrl = coordinatesLoad; // Наприклад: "https://www.google.com/maps/place/.../@50.4501,30.5234,17z"
    let parsedCoordinates = getCoordinatesFromInput(inputUrl);

    // Якщо координати не вдалося розпізнати, ставимо дефолтні
    let loadCoordinates = parsedCoordinates
        ? [parsedCoordinates.lat, parsedCoordinates.lng]
        : startLocation; // default coordinates

    // Creating map options
    var mapOptions = {
        center: loadCoordinates,
        zoom: 18,
        dragging: true,
        locale: language,
    };

    // Creating a map object
    var map = new L.map('map', mapOptions);

    // Creating a Layer object
    var layer = new L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    });

    // Adding layer to the map
    map.addLayer(layer);

    // Icon options
    var iconOptions = {
        iconUrl: '/assets/icons/entity/warehouse/marker-warehouse.png',
        iconSize: [25, 35],
    };
    // Creating a custom icon
    var customIcon = L.icon(iconOptions);

    // Creating Marker Options
    var markerOptions = {
        draggable: false,
        icon: customIcon,
    };

    // Creating a Marker
    marker = new L.Marker(loadCoordinates, markerOptions)
        .addTo(map)
        .bindPopup(getLocalizedText('warehouseLocation') + ' - ' + loadCoordinates);

    let latlng = L.latLng(loadCoordinates[0], loadCoordinates[1]);

    map.flyTo(latlng, 18);
}

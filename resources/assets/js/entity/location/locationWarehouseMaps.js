import { switchLang } from '../../grid/components/switch-lang.js';
import { getLocalizedText } from '../../localization/location/getLocalizedText.js';
import { debounce } from '../../utils/debounce.js';
import { getCoordinatesFromInput } from './utils/getCoordinatesFromInput.js';

var startLocation = [49.820434685575165, 24.003130383455048];
let language = switchLang();

if ($('#map').length) {
    // Тепер замість координат можна передати URL
    let inputUrl = coordinates; // Наприклад: "https://www.google.com/maps/place/.../@50.4501,30.5234,17z"
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

    var marker = null;

    // Creating Marker Options
    var markerOptions = {
        draggable: false,
        icon: customIcon,
    };

    if (coordinates) {
        // Creating a Marker
        marker = new L.Marker(loadCoordinates, markerOptions)
            .addTo(map)
            .bindPopup(getLocalizedText('warehouseLocation') + ' - ' + loadCoordinates);

        let latlng = L.latLng(loadCoordinates[0], loadCoordinates[1]);

        map.flyTo(latlng, 18);

        // Показуємо карту
        const mapDiv = document.getElementById('map');
        showMap(map, mapDiv);
        // Centering map on the marker
        map.setView(latlng, 18);
    }
}

function showMap(mapInstance, mapDiv) {
    mapDiv.style.visibility = 'visible';
    mapDiv.style.height = '400px'; // фіксована висота
    setTimeout(() => {
        mapInstance.invalidateSize(); // поправляємо рендер Leaflet
    }, 100);
}

function hideMap(mapDiv) {
    mapDiv.style.visibility = 'hidden';
    mapDiv.style.height = '0';
}

// Основний обробник
function handleMapInput() {
    const inputVal = $('#map-input').val().trim();
    const mapDiv = document.getElementById('map');

    if (!inputVal) {
        hideMap(mapDiv);
        coordinates = null;
        if (marker) marker.remove();
        return;
    }

    const coords = getCoordinatesFromInput(inputVal);
    if (!coords || isNaN(coords.lat) || isNaN(coords.lng)) {
        if (coords.error) {
            // очищаємо інпут або показуємо повідомлення в залежності від помилки
            $('#map-input').val('');

            if (coords.error === 'short_link') {
                alert(getLocalizedText('mapShortLinkError'));
            } else if (coords.error === 'invalid_format') {
                alert(getLocalizedText('mapInvalidFormatError'));
            }
        }

        hideMap(mapDiv);
        coordinates = null;
        if (marker) marker.remove();
        return;
    }

    console.log('Parsed coords:', coords);

    coordinates = coords;

    if (mapDiv.style.visibility === 'hidden') showMap(map, mapDiv);

    if (marker) marker.remove();

    // Creating Marker Options
    var markerOptions = {
        draggable: false,
        icon: customIcon,
    };

    marker = L.marker(coordinates, markerOptions)
        .addTo(map)
        .bindPopup(getLocalizedText('warehouseLocation') + ' - ' + coordinates);

    map.flyTo(coordinates, 18);

    // Залишаємо текст у полі інпуту
    $('#map-input').val(inputVal);

    $('#messageAdd')
        .html(getLocalizedText('mapLocationAddedSuccessMessage'))
        .css('display', 'inline-flex')
        .delay(5000)
        .slideUp(300);
}

// Додаємо дебаунс через Lodash (300ms)
const debouncedHandleMapInput = debounce(handleMapInput, 300);

// Прив'язка до input і blur
$('#map-input').on('input', debouncedHandleMapInput);

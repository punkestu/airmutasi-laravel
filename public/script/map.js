var map;
function InitMap() {
    if (map) {
        map.remove();
    }
    let newMap = L.map("map", {
        zoomControl: false,
        scrollWheelZoom: false,
        touchZoom: false,
        doubleClickZoom: false,
    }).setView([-2, 117.0577694], 5);
    const mapIndonesiaURL = "/geo/indonesia-prov.geojson";

    fetch(mapIndonesiaURL)
        .then((response) => response.json())
        .then((data) => {
            L.geoJson(data, {
                style: {
                    color: "#CACACA",
                    weight: 1.5,
                    fillColor: "#293676",
                    fillOpacity: 1,
                },
            }).addTo(newMap);
            document.getElementById("loading") &&
                document.getElementById("loading").classList.add("hidden");
        });
    return newMap;
}
map = InitMap();

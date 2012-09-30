function initialize() {
	if(GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById('map'));
		map.setCenter(new GLatLng(38.878522, -77.004805), 11);
		map.addControl(new GLargeMapControl());
		var icon = new GIcon(G_DEFAULT_ICON);
		//icon.image = "http://chart.apis.google.com/chart?cht=mm&chs=24x32&chco=FFFFFF,C00000,000000&ext=.png";
		icon.image = "/images/maps/chart.png";
		var markers = [];
		
		for (var i = 0; i < data.length; ++i) {
			var latlng = new GLatLng(data[i].Latitude, data[i].Longitude);
			var marker = new GMarker(latlng, {icon: icon});
			click(marker, data[i]);
			markers.push(marker);
		}
		
		var markerCluster = new MarkerClusterer(map, markers);
	}
}

function click(test, d) {
	GEvent.addListener(test, "click", function() {
		window.location.href = "view/ssl/" + d.Square + d.Suffix + d.Lot
		//test.openInfoWindowHtml("Marker <b>" + d.Latitude + "</b>");
	});
}
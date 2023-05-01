class MapHandler
{
	/** @param markers {{SMap.Marker}} **/
	markers = {};
	/** @param actualMarker {SMap.Marker|null} **/
	actualMarker = null;

	constructor(containerId, defaultLat, defaultLng, zoom) {
		this.defaultCoords = SMap.Coords.fromWGS84(defaultLat, defaultLng);
		this.zoom = zoom;
		this.map = new SMap(JAK.gel(containerId), this.defaultCoords, this.zoom);
		this.map.addDefaultLayer(SMap.DEF_BASE).enable();
		this.map.addDefaultControls();

		this.mapMarkerLayer = new SMap.Layer.Marker();
		this.map.addLayer(this.mapMarkerLayer);
		this.mapMarkerLayer.enable();

		const baseLayer =  new SMap.Layer.Marker();
		this.map.addLayer(baseLayer);
		baseLayer.enable();
		baseLayer.addMarker(new SMap.Marker(this.defaultCoords, 'base', {url: this.getIconUrl('base')}));
	}

	centerToBase() {
		this.map.setCenter(this.defaultCoords, true);
		this.map.setZoom(this.zoom);
	}

	setActiveMarker(id) {
		if (this.actualMarker) {
			this.actualMarker.setURL(this.getIconUrl('marker'));
		}

		if (!id) {
			this.actualMarker = null;
			this.map.setCenter(this.defaultCoords, true);
			this.map.setZoom(this.zoom);
			return;
		}

		const marker = this.markers[id];
		if (!marker) {
			return;
		}

		marker.setURL(this.getIconUrl('marker-actual'));
		this.map.setCenter(marker.getCoords(), true);
		this.map.setZoom(this.zoom);

		this.actualMarker = marker;
	}

	/** @param markers {MapMarker[]} */
	updateMarkers(markers) {
		let usedIds = [];
		for (const marker of markers) {
			usedIds.push(marker.id);
			if (this.markers[marker.id] === undefined) {
				this.markers[marker.id] = this.createMarker(marker);
				this.mapMarkerLayer.addMarker(this.markers[marker.id]);
			}
		}
	}

	/** @param marker {MapMarker} */
	createMarker(marker) {
		const coords = SMap.Coords.fromWGS84(marker.lng, marker.lat);
		return new SMap.Marker(coords, marker.id, {url: this.getIconUrl('marker')});
	}

	getIconUrl(type) {
		return '/public/icon-' + type + '.png';
	}

	onMarkerClick(fx) {
		this.map.getSignals().addListener(this, "marker-click", fx);
	}
}

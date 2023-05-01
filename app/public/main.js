const { createApp } = Vue;
const DEFAULT_LAT = 13.540160;
const DEFAULT_LNG = 49.578684;
const DEFAULT_ZOOM = 14;
const OUTDATED_SELECT = 600;

createApp({
	/**
	 * @returns {{
	 *  timer: int|null,
	 * 	lastUpdate: Date|null,
	 * 	mapMarkerLayer: object|null,
	 * 	loading: boolean,
	 * 	error: string|null,
	 *  actualEvent: Event|null,
	 * 	lastEvent: Event|null,
	 * 	map: object|null,
	 * 	events: Event[]
	 * }}
	 */
	data() {
		this.map = null;

		return {
			lastUpdate: null,
			loading: true,
			firstLoad: true,
			error: null,
			timer: null,
			events: [],
			actualEvent: null,
			actualEventSelectDate: null,
			lastEvent: null,
		}
	},
	mounted() {
		this.update();

		this.map = new MapHandler('map', DEFAULT_LAT, DEFAULT_LNG, DEFAULT_ZOOM);
		this.map.onMarkerClick((e) => {
			this.selectMarker(e.target);
		});
	},
	methods: {
		update() {
			this.loading = true;
			const request = new XMLHttpRequest();
			request.onload = (e) => {
				if (e.target.status !== 200) {
					this.error = e.target.statusText;
				} else {
					this.error = null;
					this.lastUpdate = new Date().toLocaleString();

					let response = JSON.parse(e.target.response).map((event) => Event.fromAjax(event));
					this.setEvents(response);
				}
			};
			request.onerror = (e) => {
				this.error = 'neznámá chyba při načítání';
			};
			request.onloadend = (e) => {
				this.loading = false;
				this.firstLoad = false;
				this.timer = setTimeout(() => {
					this.update();
				}, 5000);
			};
			request.open('GET', '/api/messages');
			request.send();
		},
		/** @param events {Event[]} */
		setEvents(events) {
			if (!events) {
				return;
			}
			if (this.lastEvent?.id === events[0].id) {
				if (events[0].isOutdated() && this.actualEventSelectDate?.isBefore(moment().subtract(OUTDATED_SELECT, 'seconds'))) {
					this.selectEvent(null);
				}
				return;
			}

			if (!this.lastEvent) {
				this.events = events;
			} else {
				const lastEventIndex = events.findIndex((event) => event.id === this.lastEvent.id);
				if (lastEventIndex === -1) {
					this.events = events;
				} else {
					this.events = events.slice(0, lastEventIndex).concat(this.events);
				}
			}

			this.lastEvent = events[0];
			this.updateMap();
			if (!events[0].isOutdated()) {
				this.selectEvent(events[0]);
			}
		},
		/** @param event {Event} */
		selectEvent(event) {
			if (!event) {
				this.actualEvent = null;
				this.actualEventSelectDate = null;
				this.map.setActiveMarker(null);
				return;
			}

			if (event.id === this.actualEvent?.id) {
				return;
			}

			this.actualEvent = event;
			this.actualEventSelectDate = moment();
			this.map.setActiveMarker(event.id);
		},
		selectMarker(marker) {
			const eventId = marker.getId();
			const event = this.events.find((event) => event.id === eventId);
			this.selectEvent(event || null);
		},
		updateMap() {
			/** @param event {Event} */
			this.map.updateMarkers(this.events);
			if (this.actualEvent) {
				this.map.setActiveMarker(this.actualEvent.id);
			} else {
				this.map.centerToBase();
			}
		}
	},
	computed: {
		statusText() {
			if (this.loading && !this.lastUpdate) {
				return 'načítám...';
			}
			if (this.error) {
				return this.error;
			}
			return this.lastUpdate;
		},
		statusClass() {
			if (this.error) {
				return 'text-danger';
			}
			return 'text-secondary';
		}
	}
}).mount('#app')

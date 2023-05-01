const DATE_FORMAT = 'DD.MM.YYYY HH:mm:ss';
const OUTDATED_TIMEOUT = 3600;

class Event {
	/**
	 * @param id {int}
	 * @param emailId {string}
	 * @param emailDate {string}
	 * @param emailSubject {string}
	 * @param izscrId {string|null}
	 * @param izscrDate {string|null}
	 * @param izscrName {string|null}
	 * @param title {string|null}
	 * @param object {string|null}
	 * @param clarification {string|null}
	 * @param description {string|null}
	 * @param vehiclesLocal {string|null}
	 * @param vehiclesOther {string|null}
	 * @param reporterName {string|null}
	 * @param reporterPhone {string|null}
	 * @param city {string|null}
	 * @param cityPart {string|null}
	 * @param street {string|null}
	 * @param streetNumber {string|null}
	 * @param region {string|null}
	 * @param zip {string|null}
	 * @param lat {float}
	 * @param lng {float}
	 * @param createdAt {string}
	 */
	constructor(
		id,
		emailId,
		emailDate,
		emailSubject,
		izscrId,
		izscrDate,
		izscrName,
		title,
		object,
		clarification,
		description,
		vehiclesLocal,
		vehiclesOther,
		reporterName,
		reporterPhone,
		city,
		cityPart,
		street,
		streetNumber,
		region,
		zip,
		lat,
		lng,
		createdAt,
	) {
		this.id = id;
		this.emailId = emailId;
		this.emailDate = emailDate;
		this.emailSubject = emailSubject;
		this.izscrId = izscrId;
		this.izscrDate = izscrDate;
		this.izscrName = izscrName;
		this.title = title;
		this.object = object;
		this.clarification = clarification;
		this.description = description;
		this.vehiclesLocal = vehiclesLocal;
		this.vehiclesOther = vehiclesOther;
		this.reporterName = reporterName;
		this.reporterPhone = reporterPhone;
		this.city = city;
		this.cityPart = cityPart;
		this.street = street;
		this.streetNumber = streetNumber;
		this.region = region;
		this.zip = zip;
		this.lat = lat;
		this.lng = lng;
		this.createdAt = createdAt;

		this.eventDateString = this.izscrDate || this.emailDate;
		this.eventDate = moment(this.eventDateString, DATE_FORMAT);
		this.addressString = (this.street || '')
			+ (this.streetNumber ? ' ' + this.streetNumber : '')
			+ (this.zip ? ', ' + this.zip : '')
			+ (this.city ? ', ' + this.city : '');
		this.addressString = this.addressString.replace(/^[ ,]+/g, '');

		this.vehiclesLocalString = vehiclesLocal?.replace("\n", '<br>');
		this.vehiclesOtherString = vehiclesOther?.replace("\n", '<br>');
	}

	isOutdated() {
		const outdatedDate = moment().subtract(OUTDATED_TIMEOUT, 'seconds');
		return this.eventDate.isBefore(outdatedDate);
	}

	static fromAjax(event) {
		return new Event(
			event.id,
			event.emailId,
			event.emailDate,
			event.emailSubject,
			event.izscrId,
			event.izscrDate,
			event.izscrName,
			event.title,
			event.object,
			event.clarification,
			event.description,
			event.vehiclesLocal,
			event.vehiclesOther,
			event.reporterName,
			event.reporterPhone,
			event.city,
			event.cityPart,
			event.street,
			event.streetNumber,
			event.region,
			event.zip,
			event.lat,
			event.lng,
			event.createdAt,
		);
	}
}

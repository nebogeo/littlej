/**
 ** Copyright (c) 2010 Ushahidi Inc
 ** All rights reserved
 ** Contact: team@ushahidi.com
 ** Website: http://www.ushahidi.com
 **
 ** GNU Lesser General Public License Usage
 ** This file may be used under the terms of the GNU Lesser
 ** General Public License version 3 as published by the Free Software
 ** Foundation and appearing in the file LICENSE.LGPL included in the
 ** packaging of this file. Please review the following information to
 ** ensure the GNU Lesser General Public License version 3 requirements
 ** will be met: http://www.gnu.org/licenses/lgpl.html.
 **
 **
 ** If you have questions regarding the use of this file, please contact
 ** Ushahidi developers at team@ushahidi.com.
 **
 **/

package foam.littlej.android.app.entities;

import foam.littlej.android.app.models.Model;

/**
 * @author eyedol
 */
public class Checkin extends Model implements IDbEntity {

	private int id;

	private int userId;

	private int checkinId;

	private int pending = 0;

	private String message;

	private String date;

	private String username;

	private String locationName;

	private String locationLatitude;

	private String locationLongitude;

	private String thumbnail;

	/*
	 * (non-Javadoc)
	 * 
	 * @see foam.littlej.android.app.entities.IDbEntity#getDbId()
	 */
	@Override
	public int getDbId() {
		return id;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see foam.littlej.android.app.entities.IDbEntity#setDbId(int)
	 */
	@Override
	public void setDbId(int id) {
		this.id = id;
	}

	public void setCheckinId(int checkinId) {
		this.checkinId = checkinId;
	}

	public int getCheckinId() {
		return this.checkinId;
	}

	public int getUserId() {
		return userId;
	}

	public void setUserId(int userId) {
		this.userId = userId;
	}

	public String getUsername() {
		return username;
	}

	public void setUsername(String username) {
		this.username = username;
	}

	public String getMessage() {
		return message;
	}

	public void setMessage(String message) {
		this.message = message;
	}

	public String getDate() {
		return date;
	}

	public void setDate(String date) {
		this.date = date;
	}

	public String getLocationName() {
		return locationName;
	}

	public void setLocationName(String locationName) {
		this.locationName = locationName;
	}

	public String getLocationLatitude() {
		return locationLatitude;
	}

	public void setLocationLatitude(String locationLatitude) {
		this.locationLatitude = locationLatitude;
	}

	public String getLocationLongitude() {
		return locationLongitude;
	}

	public void setLocationLongitude(String locationLongitude) {
		this.locationLongitude = locationLongitude;
	}

	public void setThumbnail(String thumbnail) {
		this.thumbnail = thumbnail;
	}

	public String getThumbnail() {
		return this.thumbnail;
	}

	public void setPending(int pending) {
		this.pending = pending;
	}

	public int getPending() {
		return this.pending;
	}

	@Override
	public boolean load() {

		return false;
	}

	@Override
	public boolean save() {
		return false;
	}
}

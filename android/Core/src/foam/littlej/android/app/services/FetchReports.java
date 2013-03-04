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
package foam.littlej.android.app.services;

import android.content.Intent;

import foam.littlej.android.app.ImageManager;
import foam.littlej.android.app.database.Database;
import foam.littlej.android.app.models.ListCheckinModel;
import foam.littlej.android.app.models.ListCommentModel;
import foam.littlej.android.app.models.ListReportModel;
import foam.littlej.android.app.net.CategoriesHttpClient;
import foam.littlej.android.app.net.CheckinHttpClient;
import foam.littlej.android.app.net.ReportsHttpClient;
import foam.littlej.android.app.util.ApiUtils;
import foam.littlej.android.app.util.Util;

/**
 * @author eyedol
 * 
 */
public class FetchReports extends SyncServices {

	private static String CLASS_TAG = FetchReports.class.getSimpleName();

	private Intent statusIntent; // holds the status of the sync and sends it to

	private int status = 113;

	public FetchReports() {
		super(CLASS_TAG);
		statusIntent = new Intent(SYNC_SERVICES_ACTION);
	}

	/**
	 * Clear saved reports
	 */
	public void clearCachedData() {
		// delete reports
		new ListReportModel().deleteReport();

		// delete checkins data
		new ListCheckinModel().deleteCheckin();

		// delete comment data
		new ListCommentModel().deleteComments();

		// delete fetched photos
		ImageManager.deleteImages(this);

		// delete pending photos
		ImageManager.deletePendingImages(this);

		// delete Open GeoSMS reports
		Database.mOpenGeoSmsDao.deleteReports();
	}

	@Override
	protected void executeTask(Intent intent) {

		new Util().log("executeTask() executing this task");
		clearCachedData();
		if (!new ApiUtils(this).isCheckinEnabled()) {

			// fetch categories
			new CategoriesHttpClient(this).getCategoriesFromWeb();
			// fetch reportsx
			status = new ReportsHttpClient(this).getAllReportFromWeb();

		} else {
			status = new CheckinHttpClient(this).getAllCheckinFromWeb();
		}

		statusIntent.putExtra("status", status);
		sendBroadcast(statusIntent);

	}
}

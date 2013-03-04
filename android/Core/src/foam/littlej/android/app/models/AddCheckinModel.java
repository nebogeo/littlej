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

package foam.littlej.android.app.models;

import java.io.File;
import java.util.List;

import foam.littlej.android.app.database.Database;
import foam.littlej.android.app.database.IMediaSchema;
import foam.littlej.android.app.entities.Checkin;
import foam.littlej.android.app.entities.Media;
import foam.littlej.android.app.entities.Photo;

public class AddCheckinModel extends Model {

	public boolean addPendingCheckin(Checkin checkin, File[] pendingPhotos) {
		boolean status;
		// add pending reports
		status = Database.mCheckin.addCheckin(checkin);
		// int id = Database.mCheckin.
		int id = Database.mCheckin.fetchPendingCheckinIdByDate(checkin.getDate());
		
		// add photos
		if (pendingPhotos != null && pendingPhotos.length > 0) {
			for (File file : pendingPhotos) {
				if (file.exists()) {
					Media media = new Media();
					media.setMediaId(0);
					media.setLink(file.getName());

					// get report ID;
					media.setCheckinId(id);
					media.setType(IMediaSchema.IMAGE);
					Database.mMediaDao.addMedia(media);
				}
			}

		}

		return status;
	}

	public boolean updatePendingCheckin(int checkinId, Checkin checkin,
			List<Photo> pendingPhotos) {
		boolean status;
		// update pending reports
		status = Database.mCheckin.updatePendingCheckin(checkinId, checkin);

		// update photos
		if (pendingPhotos != null && pendingPhotos.size() > 0) {
			// delete existing photo
			Database.mMediaDao.deleteReportPhoto(checkinId);
			for (Photo photo : pendingPhotos) {
				Media media = new Media();
				media.setMediaId(0);
				// FIXME:: this is nasty.
				String sections[] = photo.getPhoto().split("/");
				media.setLink(sections[1]);

				// get report ID
				media.setCheckinId(checkinId);
				media.setType(IMediaSchema.IMAGE);
				Database.mMediaDao.addMedia(media);
			}

		}

		return status;
	}

	public boolean deleteCheckin(int checkinId) {
		// delete checkin
		Database.mCheckin.deletePendingCheckinById(checkinId);

		// delete media
		Database.mMediaDao.deleteMediaByCheckinId(checkinId);
		return true;
	}

	public Checkin fetchPendingCheckinById(int checkinId) {
		return Database.mCheckin.fetchPendingCheckinById(checkinId);
	}

	@Override
	public boolean load() {
		// TODO Auto-generated method stub
		return false;
	}

	@Override
	public boolean save() {
		// TODO Auto-generated method stub
		return false;
	}

}

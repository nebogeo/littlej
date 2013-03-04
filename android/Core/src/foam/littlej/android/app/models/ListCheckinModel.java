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

import java.util.ArrayList;
import java.util.List;

import android.content.Context;

import foam.littlej.android.app.R;
import foam.littlej.android.app.database.Database;
import foam.littlej.android.app.database.IMediaSchema;
import foam.littlej.android.app.entities.Checkin;
import foam.littlej.android.app.entities.Media;
import foam.littlej.android.app.entities.User;
import foam.littlej.android.app.util.Util;

/**
 * @author eyedol
 */
public class ListCheckinModel extends Checkin {

	List<Checkin> mCheckins;

	@Override
	public boolean load() {
		mCheckins = Database.mCheckin.fetchAllCheckins();
		if (mCheckins != null) {
			return true;
		}

		return false;
	}

	public boolean loadCheckinByUser(int userid) {
		mCheckins = Database.mCheckin.fetchCheckinsByUserId(userid);
		if (mCheckins != null) {
			return true;
		}
		return false;
	}

	public boolean loadPendingCheckin() {
		mCheckins = Database.mCheckin.fetchAllPendingCheckins();
		if (mCheckins != null) {
			return true;
		}

		return false;
	}

	public boolean loadPendingCheckinByUser(int userid) {
		mCheckins = Database.mCheckin.fetchPendingCheckinsByUserId(userid);
		if (mCheckins != null) {
			return true;
		}
		return false;
	}

	public List<ListCheckinModel> getCheckins(Context context) {
		final List<ListCheckinModel> checkins = new ArrayList<ListCheckinModel>();
		String d = null;

		if (mCheckins != null && mCheckins.size() > 0) {
			for (Checkin item : mCheckins) {
				ListCheckinModel listCheckin = new ListCheckinModel();
				listCheckin.setDbId(item.getDbId());
				listCheckin.setCheckinId(item.getCheckinId());
				listCheckin.setUsername(getUsername(context, item.getUserId()));
				listCheckin.setDate(Util.formatDate("yyyy-MM-dd hh:mm:ss",
						item.getDate(), "MMMM dd, yyyy 'at' hh:mm:ss aaa"));
				listCheckin.setLocationLatitude(item.getLocationLatitude());
				listCheckin.setLocationLongitude(item.getLocationLongitude());
				listCheckin.setLocationName(item.getLocationName());
				listCheckin.setMessage(item.getMessage());
				listCheckin.setUserId(item.getUserId());

				// set thumbnails
				if (item.getCheckinId() == 0) {
					// get pending reports images
					d = getImage(context, item.getDbId());

				} else {
					// get fetched reports images
					d = getImage(context, item.getCheckinId());
				}

				listCheckin.setThumbnail(d);
				
				checkins.add(listCheckin);
			}
		}
		return checkins;
	}

	private String getImage(Context context, int checkinId) {
		List<Media> sMedia = Database.mMediaDao.fetchMedia(
				IMediaSchema.CHECKIN_ID, checkinId, IMediaSchema.IMAGE, 1);
		if (sMedia != null && sMedia.size() > 0) {
			
			return sMedia.get(0).getLink();
		}
		return null;
	}

	private String getUsername(Context context, int userId) {
		new Util().log("ListCheckinModel","User Id "+userId);
		List<User> sUser = Database.mUserDao.fetchUsersById(userId);
		if (sUser != null && sUser.size() > 0) {
			return sUser.get(0).getUsername();
		}
		return context.getText(R.string.unknown).toString();
	}
	
	/**
	 * Deletes all fetched reports.
	 * 
	 * @param reportId The id of the report to be deleted.
	 * 
	 * @return boolean
	 */
	public boolean deleteAllFetchedCheckin(int checkinId) {
		
		// delete fetched reports
		if(Database.mCheckin.deleteAllCheckins() )  {
			new Util().log("ListCheckinModel","Checkin deleted");
		}
		
		if( Database.mUserDao.deleteAllUsers() ) {
			new Util().log("Users: ","Users deleted");
		}

		// delete media
		if(Database.mMediaDao.deleteCheckinPhoto(checkinId) ) {
			new Util().log("Media","Media deleted");
		}
		return true;
	}
	
public boolean deleteCheckin() {
		
		// delete fetched reports
		if(Database.mCheckin.deleteAllCheckins() )  {
			new Util().log("ListCheckinModel","Checkin deleted");
		}
		
		if( Database.mUserDao.deleteAllUsers() ) {
			new Util().log("Users: ","Users deleted");
		}

		// delete media
		if(Database.mMediaDao.deleteAllMedia() ) {
			new Util().log("Media","Media deleted");
		}
		return true;
	}

	@Override
	public boolean save() {
		// TODO Auto-generated method stub
		return false;
	}

}

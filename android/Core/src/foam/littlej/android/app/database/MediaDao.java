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

package foam.littlej.android.app.database;

import java.util.ArrayList;
import java.util.List;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import foam.littlej.android.app.entities.Media;

/**
 * @author eyedol
 */
public class MediaDao extends DbContentProvider implements IMediaDao,
		IMediaSchema {

	private Cursor cursor;

	private List<Media> listMedia;

	private ContentValues initialValues;

	/**
	 * @param db
	 */
	public MediaDao(SQLiteDatabase db) {
		super(db);

	}

	@Override
	public List<Media> fetchCheckinPhoto(int checkinId) {
		listMedia = new ArrayList<Media>();
		final String selectionArgs[] = { String.valueOf(checkinId),
				String.valueOf(IMAGE) };

		final String selection = CHECKIN_ID + " =? AND " + TYPE + " =?";
		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, selectionArgs,
				null);
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}

	@Override
	public List<Media> fetchReportPhoto(int reportId) {
		listMedia = new ArrayList<Media>();

		final String selection = REPORT_ID + " = " + reportId + " AND " + TYPE
				+ " =" + IMAGE;
		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, null, null);
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}
	
	@Override
	public List<Media> fetchPendingCheckinPhoto(int checkinId) {
		listMedia = new ArrayList<Media>();

		final String selection = CHECKIN_ID + " = " + checkinId + " AND " + TYPE
				+ " =" + IMAGE;
		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, null, null);
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}

	@Override
	public List<Media> fetchPendingReportPhoto(int reportId) {
		listMedia = new ArrayList<Media>();

		final String selection = REPORT_ID + " = " + reportId + " AND " + TYPE
				+ " =" + IMAGE;
		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, null, null);
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}

	@Override
	public List<Media> fetchReportVideo(int reportId) {
		listMedia = new ArrayList<Media>();

		final String selection = REPORT_ID + " = " + reportId + " AND " + TYPE
				+ " =" + VIDEO;
		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, null, null);
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}

	@Override
	public List<Media> fetchReportAudio(int reportId) {
		listMedia = new ArrayList<Media>();
		final String selection = REPORT_ID + " = " + reportId + " AND " + TYPE
				+ " =" + AUDIO;
		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, null, null);
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}

	@Override
	public List<Media> fetchReportNews(int reportId) {
		listMedia = new ArrayList<Media>();

		final String selection = REPORT_ID + " = " + reportId + " AND " + TYPE
				+ " =" + NEWS;
		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, null, null);
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}

	@Override
	public List<Media> fetchMedia(String itemType, int itemId, int mediaType,
			int limit) {
		listMedia = new ArrayList<Media>();

		final String selection = itemType + " =" + itemId + " AND " + TYPE
				+ " =" + mediaType;

		cursor = super.query(TABLE, MEDIA_COLUMNS, selection, null, null,
				String.valueOf(limit));
		if (cursor != null) {
			cursor.moveToFirst();
			while (!cursor.isAfterLast()) {
				Media media = cursorToEntity(cursor);
				listMedia.add(media);
				cursor.moveToNext();
			}
			cursor.close();
		}
		return listMedia;
	}

	/**
	 * (non-Javadoc)
	 * 
	 * @see foam.littlej.android.app.database.IMediaDao#deleteAllMedia()
	 */
	@Override
	public boolean deleteAllMedia() {
		return super.delete(TABLE, null, null) > 0;
	}

	/**
	 * Delete all media attached to a report
	 * 
	 * @param reportId
	 *            The report id
	 */
	public boolean deleteMediaByReportId(int reportId) {
		final String selectionArgs[] = { String.valueOf(reportId) };

		final String selection = ID + " =?";
		return super.delete(TABLE, selection, selectionArgs) > 0;
	}

	/**
	 * Delete all media attached to this checkin
	 */
	public boolean deleteMediaByCheckinId(int checkinId) {
		final String selectionArgs[] = { String.valueOf(checkinId) };

		final String selection = ID + " =?";
		return super.delete(TABLE, selection, selectionArgs) > 0;
	}

	/**
	 * Update media by report Id
	 * 
	 * @param reportId
	 *            The report id
	 */
	public boolean updateMediaByReportId(int reportId) {
		return false;
	}

	/**
	 * Update media by checkin id
	 * 
	 * @param checkinId
	 *            The checkin id
	 */
	public boolean updateMediaByCheckinId(int checkinId) {
		return false;
	}

	public boolean deleteReportMediaByIdAndLink(int reportId, String link) {

		final String selectionArgs[] = { String.valueOf(reportId), link };
		final String selection = ID + " =? AND " + LINK + " =?";
		return super.delete(TABLE, selection, selectionArgs) > 0;

	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see foam.littlej.android.app.database.IMediaDao#addMedia(java.util.List)
	 */
	@Override
	public boolean addMedia(List<Media> sMedia) {
		try {
			mDb.beginTransaction();

			for (Media media : sMedia) {

				addMedia(media);
			}

			mDb.setTransactionSuccessful();
		} finally {
			mDb.endTransaction();
		}
		return true;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * foam.littlej.android.app.database.IMediaDao#addMedia(foam.littlej.android
	 * .app.entities.Media)
	 */
	@Override
	public boolean addMedia(Media media) {
		// set values
		setContentValue(media);
		return super.insert(TABLE, getContentValue()) > 0;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * foam.littlej.android.app.database.DbContentProvider#cursorToEntity(android
	 * .database.Cursor)
	 */
	@SuppressWarnings("unchecked")
	@Override
	protected Media cursorToEntity(Cursor cursor) {
		Media media = new Media();
		int idIndex;
		int reportIdIndex;
		int checkinIdIndex;
		int typeIndex;
		int linkIndex;

		if (cursor != null) {
			if (cursor.getColumnIndex(ID) != -1) {
				idIndex = cursor.getColumnIndexOrThrow(ID);
				media.setDbId(cursor.getInt(idIndex));
			}

			if (cursor.getColumnIndex(REPORT_ID) != -1) {
				reportIdIndex = cursor.getColumnIndexOrThrow(REPORT_ID);
				media.setReportId(cursor.getInt(reportIdIndex));
			}

			if (cursor.getColumnIndex(CHECKIN_ID) != -1) {
				checkinIdIndex = cursor.getColumnIndexOrThrow(CHECKIN_ID);
				media.setCheckinId(cursor.getInt(checkinIdIndex));
			}

			if (cursor.getColumnIndex(TYPE) != -1) {
				typeIndex = cursor.getColumnIndexOrThrow(TYPE);
				media.setType(cursor.getInt(typeIndex));
			}

			if (cursor.getColumnIndex(LINK) != -1) {
				linkIndex = cursor.getColumnIndexOrThrow(LINK);
				media.setLink(cursor.getString(linkIndex));
			}

		}
		return media;
	}

	private void setContentValue(Media media) {
		initialValues = new ContentValues();
		initialValues.put(MEDIA_ID, media.getMediaId());
		initialValues.put(REPORT_ID, media.getReportId());
		initialValues.put(CHECKIN_ID, media.getCheckinId());
		initialValues.put(TYPE, media.getType());
		initialValues.put(LINK, media.getLink());
	}

	private ContentValues getContentValue() {
		return initialValues;
	}

	public boolean deleteReportNews(int reportId) {
		final String selectionArgs[] = { String.valueOf(reportId), String.valueOf(IMediaSchema.NEWS) };
		final String selection = REPORT_ID + " =? AND " + TYPE + " =?";
		return super.delete(TABLE, selection, selectionArgs) > 0 ;
	}
	
	public boolean deleteReportPhoto(int reportId) {
		final String selectionArgs[] = { String.valueOf(reportId), String.valueOf(IMediaSchema.IMAGE) };
		final String selection = REPORT_ID + " =? AND " + TYPE + " =?";
		return super.delete(TABLE, selection, selectionArgs) > 0 ;
	}
	
	public boolean deleteCheckinPhoto(int checkinId) {
		final String selectionArgs[] = { String.valueOf(checkinId), String.valueOf(IMediaSchema.IMAGE) };
		final String selection = CHECKIN_ID + " =? AND " + TYPE + " =?";
		return super.delete(TABLE, selection, selectionArgs) > 0 ;
	}
}

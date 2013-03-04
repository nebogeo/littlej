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
package foam.littlej.android.app.adapters;

import java.util.List;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import foam.littlej.android.app.R;
import foam.littlej.android.app.models.ListCommentModel;
import foam.littlej.android.app.util.Util;

/**
 * @author eyedol
 * 
 */
public class CommentAdapter extends BaseListAdapter<ListCommentModel> {

	private ListCommentModel mListCommentModel;

	private List<ListCommentModel> items;

	private int[] colors;

	/**
	 * @param context
	 */
	public CommentAdapter(Context context) {
		super(context);
		colors = new int[] { R.drawable.odd_row_rounded_corners,
				R.drawable.even_row_rounded_corners };
	}

	class Widgets extends foam.littlej.android.app.views.View {

		public Widgets(View view) {
			super(view);
			this.commentAuthor = (TextView) view
					.findViewById(R.id.comment_author);
			this.commentDate = (TextView) view.findViewById(R.id.comment_date);
			this.commentDescription = (TextView) view
					.findViewById(R.id.comment_description);

		}

		TextView commentAuthor;
		TextView commentDate;
		TextView commentDescription;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see android.widget.Adapter#getView(int, android.view.View,
	 * android.view.ViewGroup)
	 */
	@Override
	public View getView(int position, View view, ViewGroup viewGroup) {
		int colorPosition = position % colors.length;
		View row = inflater.inflate(R.layout.comment_item, viewGroup, false);
		row.setBackgroundResource(colors[colorPosition]);
		Widgets widgets = (Widgets) row.getTag();

		if (widgets == null) {
			widgets = new Widgets(row);
			row.setTag(widgets);
		}

		// FIXME: only show the first item for now. In the future only get one
		// item
		widgets.commentAuthor.setText(getItem(position).getCommentAuthor());
		widgets.commentDate.setText(Util.formatDate("yyyy-MM-dd HH:mm:ss",
				getItem(position).getCommentDate(), "MMM dd, yyyy"));
		widgets.commentDescription.setText(getItem(position)
				.getCommentDescription());
		return row;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * foam.littlej.android.app.adapters.BaseListAdapter#refresh(android.content
	 * .Context)
	 */
	@Override
	public void refresh() {

	}

	public void refresh(int reportId) {
		mListCommentModel = new ListCommentModel();
		final boolean loaded = mListCommentModel.load(reportId);
		if (loaded) {
			items = mListCommentModel.getCommentsByReportId(reportId);
			this.setItems(items);
		}
	}

	public void refreshCheckinComment(int checkinId) {
		mListCommentModel = new ListCommentModel();
		final boolean loaded = mListCommentModel.loadCheckinComment(checkinId);
		if (loaded) {
			items = mListCommentModel.getCommentsByCheckinId(checkinId);
			this.setItems(items);
		}
	}

}

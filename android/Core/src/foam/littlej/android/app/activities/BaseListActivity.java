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

package foam.littlej.android.app.activities;

import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.widget.AdapterView;
import android.widget.ListView;

import foam.littlej.android.app.R;
import foam.littlej.android.app.adapters.BaseListAdapter;
import foam.littlej.android.app.models.Model;
import foam.littlej.android.app.tasks.ProgressTask;
import foam.littlej.android.app.util.Objects;
import foam.littlej.android.app.views.View;

/**
 * BaseListActivity Add shared functionality that exists between all List
 * Activities
 */
public abstract class BaseListActivity<V extends View, M extends Model, L extends BaseListAdapter<M>>
		extends BaseActivity<V> implements AdapterView.OnItemClickListener,
		AdapterView.OnItemSelectedListener {

	/**
	 * ListView resource id
	 */
	private final int listViewId;

	/**
	 * ListAdpater class
	 */
	private final Class<L> adapterClass;

	/**
	 * ListAdapter
	 */
	protected L adapter;

	/**
	 * ListView
	 */
	protected ListView listView;

	/**
	 * BaseListActivity
	 * 
	 * @param view
	 *            View clas type
	 * @param adapter
	 *            List adapter class type
	 * @param layout
	 *            layout resource id
	 * @param menu
	 *            menu resource id
	 * @param listView
	 *            list view resource id
	 */
	protected BaseListActivity(Class<V> view, Class<L> adapter, int layout,
			int menu, int listView) {
		super(view, layout, menu);
		this.adapterClass = adapter;
		this.listViewId = listView;
	}

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		if (listViewId != 0) {
			listView = findListViewById(listViewId);
			if (headerView() != null) {
				listView.addHeaderView(headerView());
			}
			listView.setOnItemClickListener(this);
			android.view.View emptyView = findViewById(android.R.id.empty);
			if (emptyView != null) {
				listView.setEmptyView(emptyView);
			}

			adapter = Objects.createInstance(adapterClass, Context.class, this);
			listView.setAdapter(adapter);
			listView.setFocusable(true);
		}
	}

	/**
	 * Called after ListAdapter has been loaded
	 * 
	 * @param success
	 *            true is successfully loaded
	 */
	protected abstract void onLoaded(boolean success);

	protected abstract android.view.View headerView();

	@Override
	protected void onResume() {
		super.onResume();
		// new LoadingTask(this).execute((String)null);
	}

	@SuppressWarnings("unchecked")
	protected M getItem(int position) {
		return (M) listView.getItemAtPosition(position);
	}

	@SuppressWarnings("unchecked")
	protected M getSelectedItem() {
		return (M) listView.getSelectedItem();
	}

	public void onItemSelected(AdapterView<?> adapterView,
			android.view.View view, int position, long id) {
	}

	public void onNothingSelected(AdapterView<?> adapterView) {
	}

	/**
	 * ProgressTask sub-class for showing Loading... dialog while the
	 * BaseListAdapter loads the data
	 */
	protected class LoadingTask extends ProgressTask {
		public LoadingTask(FragmentActivity activity) {
			super(activity, R.string.loading_);
		}

		@Override
		protected Boolean doInBackground(String... args) {
			adapter.refresh();
			return true;
		}

		@Override
		protected void onPostExecute(Boolean success) {
			super.onPostExecute(success);
			onLoaded(success);
		}
	}

}

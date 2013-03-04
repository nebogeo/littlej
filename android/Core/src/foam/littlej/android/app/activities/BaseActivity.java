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

import java.lang.reflect.InvocationTargetException;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.ActionBar;
import android.support.v4.app.FragmentActivity;
import android.support.v4.view.Menu;
import android.support.v4.view.MenuItem;
import android.view.KeyEvent;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import foam.littlej.android.app.Preferences;
import foam.littlej.android.app.R;
import foam.littlej.android.app.util.Objects;
import foam.littlej.android.app.util.Util;
import foam.littlej.android.app.views.View;

/**
 * BaseActivity Add shared functionality that exists between all Activities
 */
public abstract class BaseActivity<V extends View> extends FragmentActivity {

	/**
	 * Layout resource id
	 */
	protected final int layout;

	/**
	 * Menu resource id
	 */
	protected final int menu;

	/**
	 * View class
	 */
	protected final Class<V> viewClass;

	/**
	 * View
	 */
	protected V view;

	protected ActionBar actionBar;

	/**
	 * BaseActivity
	 * 
	 * @param view
	 *            View class
	 * @param layout
	 *            layout resource id
	 * @param menu
	 *            menu resource id
	 */
	protected BaseActivity(Class<V> view, int layout, int menu) {
		this.viewClass = view;
		this.layout = layout;
		this.menu = menu;
	}

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		log("onCreate");
		actionBar = getSupportActionBar();
		actionBar.setDisplayHomeAsUpEnabled(true);

		if (layout != 0) {
			setContentView(layout);
		}

		view = Objects.createInstance(viewClass, Activity.class, this);
	}

	@Override
	protected void onStart() {
		super.onStart();
		log("onStart");
	}

	@Override
	protected void onRestart() {
		super.onRestart();
		log("onRestart");
	}

	@Override
	protected void onResume() {
		super.onResume();
		log("onResume");
	}

	@Override
	protected void onPause() {
		super.onPause();
		log("onPause");
	}

	@Override
	protected void onStop() {
		super.onStop();
		log("onStop");
	}

	@Override
	protected void onDestroy() {
		super.onDestroy();
		log("onDestroy");
	}

	protected void setActionBarTitle(String title) {
		getSupportActionBar().setTitle(title);
	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK && event.getRepeatCount() == 0) {
			log("onKeyDown KEYCODE_BACK");
		}
		return super.onKeyDown(keyCode, event);
	}

	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		log("onActivityResult");
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		if (this.menu != 0) {
			getMenuInflater().inflate(this.menu, menu);
			return true;
		}
		return false;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		return super.onOptionsItemSelected(item);
	}

	@Override
	public boolean onContextItemSelected(android.view.MenuItem item) {
		return super.onContextItemSelected(item);
	}

	public void openActivityOrFragment(Intent intent) {
		// Default implementation simply calls startActivity
		startActivity(intent);
	}

	protected void shareText(String shareItem) {

		final Intent intent = new Intent(Intent.ACTION_SEND);
		intent.setType("text/plain");
		intent.putExtra(Intent.EXTRA_TEXT, shareItem);

		startActivity(Intent.createChooser(intent,
				getText(R.string.title_share)));
	}

	protected void sharePhoto(String path) {

		// TODO: consider bringing in shortlink to session
		Preferences.loadSettings(this);
		final String reportUrl = Preferences.domain;
		final String shareString = getString(R.string.share_template, " ",
				" "+reportUrl);
		final Intent intent = new Intent(Intent.ACTION_SEND);
		intent.setType("image/jpg");
		intent.putExtra(Intent.EXTRA_STREAM, Uri.parse("file://" + path));
		intent.putExtra(Intent.EXTRA_TEXT, shareString);
		startActivityForResult(
				Intent.createChooser(intent, getText(R.string.title_share)), 0);
		setResult(RESULT_OK);

	}

	/**
	 * Converts an intent into a {@link Bundle} suitable for use as fragment
	 * arguments.
	 */
	public static Bundle intentToFragmentArguments(Intent intent) {
		Bundle arguments = new Bundle();
		if (intent == null) {
			return arguments;
		}

		final Uri data = intent.getData();
		if (data != null) {
			arguments.putParcelable("_uri", data);
		}

		final Bundle extras = intent.getExtras();
		if (extras != null) {
			arguments.putAll(intent.getExtras());
		}

		return arguments;
	}

	/**
	 * Converts a fragment arguments bundle into an intent.
	 */
	public static Intent fragmentArgumentsToIntent(Bundle arguments) {
		Intent intent = new Intent();
		if (arguments == null) {
			return intent;
		}

		final Uri data = arguments.getParcelable("_uri");
		if (data != null) {
			intent.setData(data);
		}

		intent.putExtras(arguments);
		intent.removeExtra("_uri");
		return intent;
	}

	protected EditText findEditTextById(int id) {
		return (EditText) findViewById(id);
	}

	protected ListView findListViewById(int id) {
		return (ListView) findViewById(id);
	}

	protected TextView findTextViewById(int id) {
		return (TextView) findViewById(id);
	}

	protected Spinner findSpinnerById(int id) {
		return (Spinner) findViewById(id);
	}

	protected TimePicker findTimePickerById(int id) {
		return (TimePicker) findViewById(id);
	}

	protected Button findButtonById(int id) {
		return (Button) findViewById(id);
	}

	protected ImageView findImageViewById(int id) {
		return (ImageView) findViewById(id);
	}

	protected void log(String message) {
		new Util().log(message);
	}

	protected void log(String format, Object... args) {
		new Util().log( String.format(format, args));	
	}

	protected void log(String message, Exception ex) {
		new Util().log(message, ex);
	}

	protected void toastLong(String message) {
		Toast.makeText(this, message, Toast.LENGTH_LONG).show();
	}

	protected void toastLong(int message) {
		Toast.makeText(this, getText(message), Toast.LENGTH_LONG).show();
	}

	protected void toastLong(String format, Object... args) {
		Toast.makeText(this, String.format(format, args), Toast.LENGTH_LONG)
				.show();
	}

	protected void toastLong(CharSequence message) {
		Toast.makeText(this, message.toString(), Toast.LENGTH_LONG).show();
	}

	protected void toastShort(String message) {
		Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
	}

	protected void toastShort(String format, Object... args) {
		Toast.makeText(this, String.format(format, args), Toast.LENGTH_SHORT)
				.show();
	}

	protected void toastShort(int message) {
		Toast.makeText(this, getText(message), Toast.LENGTH_SHORT).show();
	}

	protected void toastShort(CharSequence message) {
		Toast.makeText(this, message.toString(), Toast.LENGTH_SHORT).show();
	}

	@SuppressWarnings("unchecked")
	protected <T> T createInstance(Class<?> type, Class<?> constructor,
			Object... params) {
		try {
			return (T) type.getConstructor(constructor).newInstance(params);
		} catch (InstantiationException e) {
			log("InstantiationException", e);
		} catch (IllegalAccessException e) {
			log("IllegalAccessException", e);
		} catch (InvocationTargetException e) {
			log("InvocationTargetException", e);
		} catch (NoSuchMethodException e) {
			log("NoSuchMethodException", e);
		}
		return null;
	}
}

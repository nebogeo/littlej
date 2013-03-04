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

package foam.littlej.android.app.views;

import java.lang.annotation.Annotation;
import java.lang.reflect.Field;

import android.app.Activity;
import android.app.ProgressDialog;

import foam.littlej.android.app.R;
import foam.littlej.android.app.util.Util;

/**
 * Base class for Views
 * 
 * Handles dynamically loading all the sub-classes members tagged with @Widget
 * annotation
 */
public abstract class View {
	
	public ProgressDialog dialog;

	/**
	 * View Map View
	 * 
	 * @param activity
	 *            Activity
	 */
	public View(Activity activity) {
		this.dialog = new ProgressDialog(activity);
		this.dialog.setCancelable(true);
		this.dialog.setIndeterminate(true);
		this.dialog.setMessage(activity.getResources().getString(
				R.string.uploading));
		
		for (Class<?> clazz : new Class[] { getClass(),
				getClass().getSuperclass() }) {
			if (clazz != null && View.class.isAssignableFrom(clazz)) {
				for (Field field : clazz.getDeclaredFields()) {
					try {
						Annotation annotation = field
								.getAnnotation(Widget.class);
						if (annotation instanceof Widget) {
							Widget widgetAnnotation = (Widget) annotation;
							if (!field.isAccessible()) {
								field.setAccessible(true);
							}
							field.set(this, activity
									.findViewById(widgetAnnotation.value()));
						}
					} catch (IllegalArgumentException e) {
						new Util().log("IllegalArgumentException", e);
					} catch (IllegalAccessException e) {
						new Util().log("IllegalAccessException", e);
					}
				}
			}
		}
	}

	/**
	 * View
	 * 
	 * @param view
	 *            View
	 */
	public View(android.view.View view) {
		
		for (Class<?> clazz : new Class[] { getClass(),
				getClass().getSuperclass() }) {
			if (clazz != null && View.class.isAssignableFrom(clazz)) {
				for (Field field : clazz.getDeclaredFields()) {
					try {
						Annotation annotation = field
								.getAnnotation(Widget.class);
						if (annotation instanceof Widget) {
							Widget widgetAnnotation = (Widget) annotation;
							if (!field.isAccessible()) {
								field.setAccessible(true);
							}
							field.set(this,
									view.findViewById(widgetAnnotation.value()));
						}
					} catch (IllegalArgumentException e) {
						new Util().log("IllegalArgumentException", e);
					} catch (IllegalAccessException e) {
						new Util().log("IllegalAccessException", e);
						
					}
				}
			}
		}
	}

}

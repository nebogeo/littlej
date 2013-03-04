package foam.littlej.android.app.views;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.google.android.maps.OverlayItem;
import foam.littlej.android.app.R;
import foam.littlej.android.app.ReportMapOverlayItem;
import foam.littlej.android.app.ui.phone.ViewReportActivity;
import foam.littlej.android.app.util.ImageViewWorker;

public class ReportMapBallonOverlayView<Item extends OverlayItem> extends
		BalloonOverlayView<ReportMapOverlayItem> {

	private TextView title;

	private TextView snippet;

	private static TextView readMore;

	private ImageView image;

	private static Activity mActivity;

	public ReportMapBallonOverlayView(Context context, int balloonBottomOffset,
			Activity activity) {
		super(context, balloonBottomOffset);
		mActivity = activity;
	}

	@Override
	protected void setupView(Context context, final ViewGroup parent) {
		// inflate our custom layout into parent
		LayoutInflater inflater = (LayoutInflater) context
				.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		android.view.View v = inflater.inflate(R.layout.map_balloon_overlay,
				parent);

		// setup our fields
		title = (TextView) v.findViewById(R.id.balloon_item_title);
		snippet = (TextView) v.findViewById(R.id.balloon_item_snippet);
		readMore = (TextView) v.findViewById(R.id.balloon_item_readmore);
		image = (ImageView) v.findViewById(R.id.balloon_item_image);
	}

	public static void viewReports(final int id, final String filterCategory) {
		launchViewReport(id, filterCategory);

		readMore.setOnClickListener(new OnClickListener() {
			public void onClick(android.view.View view) {
				launchViewReport(id, filterCategory);
			}
		});

	}

	@Override
	protected void setBalloonData(ReportMapOverlayItem item, ViewGroup parent) {

		title.setText(item.getTitle());
		snippet.setText(item.getSnippet());
		getPhoto(item.getImage(), image);
		
		if (item.getImage() == null) {

			image.setImageResource(R.drawable.report_icon);
					
		} else {
			getPhoto(item.getImage(), image);

		}
		
	}

	private static void launchViewReport(int position,
			final String filterCategory) {
		Intent i = new Intent(mActivity, ViewReportActivity.class);
		i.putExtra("id", position);
		if (filterCategory != null
				&& !filterCategory.equalsIgnoreCase(mActivity
						.getString(R.string.all_categories))) {
			i.putExtra("category", filterCategory);
		} else {
			i.putExtra("category", "");
		}
		mActivity.startActivityForResult(i, 0);
		mActivity
				.overridePendingTransition(R.anim.home_enter, R.anim.home_exit);

	}
	
	private void getPhoto(String fileName, ImageView imageView) {
		ImageViewWorker imageWorker = new ImageViewWorker(mActivity);
		imageWorker.setImageFadeIn(true);
		imageWorker.loadImage(fileName, imageView, true, 0);
	}

}


package foam.littlej.android.app;

import java.util.List;

import android.graphics.drawable.Drawable;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.view.ViewGroup.LayoutParams;

import com.google.android.maps.GeoPoint;
import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.MapController;
import com.google.android.maps.MapView;
import com.google.android.maps.Overlay;
import com.google.android.maps.OverlayItem;
import foam.littlej.android.app.views.BalloonOverlayView;

/**
 * An abstract extention to the ItemizedOverlay for displaying information on a
 * ballon upon a tap of each marker overlay.
 * 
 * @credits - http://github.com/jgilfelt/android-mapviewballoons/
 * @author Jeff Gilfelt
 */
public abstract class MapItemizedOverlay<Item extends OverlayItem> extends ItemizedOverlay<Item> {

    private MapView mapView;

    private BalloonOverlayView<Item> balloonView;

    private View clickRegion;

    private View closeRegion;

    private int viewOffset;

    final MapController mc;

    private Item currentFocusedItem;

    private int currentFocusedIndex;

    /**
     * @param marker - An icon to be drawn on the map for each item in the
     *            overlay.
     * @param mapView - The map view upon which the overlay item will be drawn.
     */
    public MapItemizedOverlay(Drawable marker, MapView mapView) {

        super(marker);

        this.mapView = mapView;
        this.viewOffset = 30;
        this.mc = mapView.getController();

    }

    /**
     * Set the horizontal distance between the marker and the bottom of the
     * information balloon. The default is 0 which works well for center bounded
     * markers. If your marker is center-bottom bounded, call this before adding
     * overlay items to ensure the balloon hovers exactly above the marker.
     * 
     * @param pixels - The padding between the center point and the bottom of
     *            the information balloon.
     */
    public void setBalloonBottomOffset(int pixels) {
        viewOffset = pixels;
    }

    public int getBalloonBottomOffset() {
        return viewOffset;
    }

    /**
     * Override this method to handle a "tap" on a balloon. By default, does
     * nothing and returns false.
     * 
     * @param index - The index of the item whose balloon is tapped.
     * @param item - The item whose balloon is tapped.
     * @return true if you handled the tap, otherwise false.
     */
    protected boolean onBalloonTap(int index, Item item) {
        return false;
    }

    /**
     * Override this method to perform actions upon an item being tapped before
     * its balloon is displayed.
     * 
     * @param index - The index of the item tapped.
     */
    protected void onBalloonOpen(int index) {
    }

    /*
     * (non-Javadoc)
     * @see com.google.android.maps.ItemizedOverlay#onTap(int)
     */
    @Override
    // protected final boolean onTap(int index) {
    public final boolean onTap(int index) {

        currentFocusedIndex = index;
        currentFocusedItem = createItem(index);
        setLastFocusedIndex(index);

        onBalloonOpen(index);
        createAndDisplayBalloonOverlay();

        mc.animateTo(currentFocusedItem.getPoint());

        return true;
    }

    /**
     * Creates the balloon view. Override to create a sub-classed view that can
     * populate additional sub-views.
     */
    protected BalloonOverlayView<Item> createBalloonOverlayView() {
        return new BalloonOverlayView<Item>(getMapView().getContext(), getBalloonBottomOffset());
    }

    /**
     * Expose map view to subclasses. Helps with creation of balloon views.
     */
    protected MapView getMapView() {
        return mapView;
    }

    /**
     * Sets the visibility of this overlay's balloon view to GONE and unfocus
     * the item.
     */
    public void hideBalloon() {
        if (balloonView != null) {
            balloonView.setVisibility(View.GONE);
        }
        currentFocusedItem = null;
    }

    /**
     * Hides the balloon view for any other BalloonItemizedOverlay instances
     * that might be present on the MapView.
     * 
     * @param overlays - list of overlays (including this) on the MapView.
     */
    private void hideOtherBalloons(List<Overlay> overlays) {

        for (Overlay overlay : overlays) {
            if (overlay instanceof MapItemizedOverlay<?> && overlay != this) {
                ((MapItemizedOverlay<?>)overlay).hideBalloon();
            }
        }

    }

    /**
     * Sets the onTouchListener for the balloon being displayed, calling the
     * overridden {@link #onBalloonTap} method.
     */
    private OnTouchListener createBalloonTouchListener() {
        return new OnTouchListener() {

            float startX;

            float startY;

            public boolean onTouch(View v, MotionEvent event) {

                View l = ((View)v.getParent()).findViewById(R.id.balloon_main_layout);
                Drawable d = l.getBackground();

                if (event.getAction() == MotionEvent.ACTION_DOWN) {
                    int[] states = {
                        android.R.attr.state_pressed
                    };
                    if (d.setState(states)) {
                        d.invalidateSelf();
                    }
                    startX = event.getX();
                    startY = event.getY();
                    return true;
                } else if (event.getAction() == MotionEvent.ACTION_UP) {
                    int newStates[] = {};
                    if (d.setState(newStates)) {
                        d.invalidateSelf();
                    }
                    if (Math.abs(startX - event.getX()) < 40
                            && Math.abs(startY - event.getY()) < 40) {
                        // call overridden method
                        onBalloonTap(currentFocusedIndex, currentFocusedItem);
                    }
                    return true;
                } else {
                    return false;
                }

            }
        };
    }

    /*
     * (non-Javadoc)
     * @see com.google.android.maps.ItemizedOverlay#getFocus()
     */
    @Override
    public Item getFocus() {
        return currentFocusedItem;
    }

    /*
     * (non-Javadoc)
     * @see com.google.android.maps.ItemizedOverlay#setFocus(Item)
     */
    @Override
    public void setFocus(Item item) {
        super.setFocus(item);
        currentFocusedIndex = getLastFocusedIndex();
        currentFocusedItem = item;
        if (currentFocusedItem == null) {
            hideBalloon();
        } else {
            createAndDisplayBalloonOverlay();
        }
    }

    /**
     * Creates and displays the balloon overlay by recycling the current balloon
     * or by inflating it from xml.
     * 
     * @return true if the balloon was recycled false otherwise
     */
    private boolean createAndDisplayBalloonOverlay() {
        boolean isRecycled;
        if (balloonView == null) {
            balloonView = createBalloonOverlayView();
            clickRegion = (View)balloonView.findViewById(R.id.balloon_inner_layout);
            clickRegion.setOnTouchListener(createBalloonTouchListener());
            closeRegion = (View)balloonView.findViewById(R.id.balloon_close);
            if (closeRegion != null) {
                closeRegion.setOnClickListener(new OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        hideBalloon();
                    }
                });
            }
            isRecycled = false;
        } else {
            isRecycled = true;
        }

        balloonView.setVisibility(View.GONE);

        List<Overlay> mapOverlays = mapView.getOverlays();
        if (mapOverlays.size() > 1) {
            hideOtherBalloons(mapOverlays);
        }

        if (currentFocusedItem != null)
            balloonView.setData(currentFocusedItem);

        GeoPoint point = currentFocusedItem.getPoint();
        MapView.LayoutParams params = new MapView.LayoutParams(LayoutParams.WRAP_CONTENT,
                LayoutParams.WRAP_CONTENT, point, MapView.LayoutParams.BOTTOM_CENTER);
        params.mode = MapView.LayoutParams.MODE_MAP;

        balloonView.setVisibility(View.VISIBLE);

        if (isRecycled) {
            balloonView.setLayoutParams(params);
        } else {
            mapView.addView(balloonView, params);
        }

        return isRecycled;
    }

}

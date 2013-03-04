
package foam.littlej.android.app.services;


import android.app.IntentService;
import android.app.NotificationManager;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.wifi.WifiManager;
import android.os.PowerManager;

import foam.littlej.android.app.Preferences;
import foam.littlej.android.app.util.Util;


public abstract class SyncServices extends IntentService {

	public static String SYNC_SERVICES_ACTION = "foam.littlej.android.app.services.sync";
	
	public static String UPLOAD_CHECKIN_SERVICES_ACTION = "foam.littlej.android.app.services.uploadcheckin";
	
	public static String FETCH_CHECKIN_SERVICES_ACTION = "foam.littlej.android.app.services.fetchcheckin";
	
	public static String FETCH_CHECKIN_COMMENTS_SERVICES_ACTION = "foam.littlej.android.app.services.fetchcheckincomments";
	
	public static String FETCH_REPORT_COMMENTS_SERVICES_ACTION = "foam.littlej.android.app.services.fetchreportcomments";
	
	public static String UPLOAD_COMMENT_SERVICES_ACTION = "foam.littlej.android.app.services.uploadcomment";
	
    protected static String CLASS_TAG = SyncServices.class.getSimpleName();

    protected static PowerManager.WakeLock mStartingService = null;

    protected static WifiManager.WifiLock wifilock = null;

    protected NotificationManager notificationManager;

    protected static final Object mStartingServiceSync = new Object();

    public SyncServices(String name) {
        super(name);
    }

    synchronized private static PowerManager.WakeLock getPhoneWakeLock(Context context) {
        if (mStartingService == null) {
            PowerManager mgr = (PowerManager)context.getSystemService(Context.POWER_SERVICE);
            mStartingService = mgr.newWakeLock(PowerManager.PARTIAL_WAKE_LOCK, CLASS_TAG);
        }
        return mStartingService;
    }

    synchronized private static WifiManager.WifiLock getPhoneWifiLock(Context context) {
        if (wifilock == null) {

            WifiManager manager = (WifiManager)context.getSystemService(Context.WIFI_SERVICE);
            wifilock = manager.createWifiLock(WifiManager.WIFI_MODE_FULL, CLASS_TAG);
        }
        return wifilock;
    }

    protected static void sendWakefulTask(Context context, Intent i) {
        getPhoneWakeLock(context.getApplicationContext()).acquire();
        getPhoneWifiLock(context.getApplicationContext()).acquire();
        context.startService(i);
    }

    public static void sendWakefulTask(Context context, Class<?> classService) {
        sendWakefulTask(context, new Intent(context, classService));
    }

    /*
     * Subclasses must implement this method so it executes any tasks
     * implemented in it.
     */
    protected abstract void executeTask(Intent intent);

    @Override
    public void onCreate() {
        super.onCreate();
        // load setting. Just in case someone changes a setting
        Preferences.loadSettings(this);
    }

    /**
     * {@inheritDoc} Perform a task as implemented by the executeTask()
     */
    @Override
    protected void onHandleIntent(Intent intent) {
    	new Util().log( "onHandleIntent(): running service");
        try {

            boolean isConnected = Util.isConnected(this);

            // check if we have internet
            if (!isConnected) {
                // Enable the Connectivity Changed Receiver to listen for
                // connection
                // to a network
                // so we can execute pending messages.
                PackageManager pm = getPackageManager();
                ComponentName connectivityReceiver = new ComponentName(this,
                        ConnectivityChangedReceiver.class);
                pm.setComponentEnabledSetting(connectivityReceiver,
                        PackageManager.COMPONENT_ENABLED_STATE_ENABLED,
                        PackageManager.DONT_KILL_APP);

            } else {

                // execute the scheduled task
                executeTask(intent);
            }
        } finally {

            if (getPhoneWakeLock(this.getApplicationContext()).isHeld()
                    && getPhoneWakeLock(this.getApplicationContext()) != null) {
                getPhoneWakeLock(this.getApplicationContext()).release();
            }
            
            if (getPhoneWifiLock(this.getApplicationContext()).isHeld()
                    && getPhoneWifiLock(this.getApplicationContext()) != null) {
                getPhoneWifiLock(this.getApplicationContext()).release();
            }
        }
    }

    @Override
   public void onDestroy() {
        //release resources
        if (getPhoneWifiLock(this.getApplicationContext()).isHeld()
                && getPhoneWifiLock(this.getApplicationContext()) != null) {
            getPhoneWifiLock(this.getApplicationContext()).release();
        }
        
        if (getPhoneWakeLock(this.getApplicationContext()).isHeld()
                && getPhoneWakeLock(this.getApplicationContext()) != null) {
            getPhoneWakeLock(this.getApplicationContext()).release();
        }

    }
}

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

import java.util.List;

import foam.littlej.android.app.entities.OfflineReport;

/**
 * @author eyedol
 */
public interface IOfflineReportDao {

    public List<OfflineReport> fetchAllOfflineIncidents();

    public boolean deleteAllOfflineReport();
    
 // add reports
    public boolean addOfflineReport(OfflineReport offlineReport);

    // add report
    public boolean addOfflineReport(List<OfflineReport> offlineReport);
}
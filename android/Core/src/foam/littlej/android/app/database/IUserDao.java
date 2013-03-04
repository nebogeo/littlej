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

import foam.littlej.android.app.entities.User;

/**
 * @author eyedol
 */
public interface IUserDao {

    public List<User> fetchUsersById(int userId);
    
    public List<User> fetchUsers();
    
    // add user
    public boolean addUser(User user);

    // add users
    public boolean addUser(List<User> user);

    public boolean deleteAllUsers();
}

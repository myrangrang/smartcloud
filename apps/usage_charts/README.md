usage_charts is a Fork of this StorageCharts/ocStorage. The intent is to do bug fixes of this plugins. Make it compatible with 
OC6. Longterm goal is to change the charts over to Google Charts so that one doen's thave to deal with HighChart licesening.

Original Info:
oc-apps ocStorage
=================
This is the main repository for **ocStorage**.
ocStorage is an [ownCloud](http://owncloud.org/) application that draws graphs about used storage. 
ocStorage is a fork of dl_StorageCharts (c) 2012 Xavier Beurois

Quick Overview
--------------
- ocStorage stores the disk space used by each user of ownCloud: at most one record per user per day in the table `oc_usage_charts`.
- The reliability of the numbers displayed depends on the ownCloud API - ocStorage doesn't "explore" disk directories any longer.
- Three graphs are available:
  1. A pie with space used / free ratio,
  2. A histogram with used space along the last seven days,
  3. Same histogram along the last twelve months.
- User preferences are stored in the table named `oc_usage_charts_uconf`.
Each user can select which graph to display, the order of the graphs and the units for both histograms.
- Of course, each user sees its own data, but "admin" users are able to display graphs grouping all users.

Install
-------
* Click the "Download Snapshot" link above, this will create a ".zip" file on the fly.
* Save this file then uncompress it into the ownCloup apps folder (/var/www/owncloud/apps/usage_charts)
As an admin, activate this application if needed.

Licensing
---------
**ocStorage** is distributed under the terms of the [GNU Affero General Public License](http://www.gnu.org/licenses/agpl-3.0.html)

Todo
----
+ Rewrite SQL requests about histogram last seven days
+ Rewrite SQL requests about histogram last twelve months
+ Delete data more than 12 months old
+ Redesign database: less records, less computations, more JSON

--------
ABOUT
--------
The attendance block was initially development by the Human Logic Development Team, Dubai, UAE.
Visit them online at www.human-logic.com

Development was continued by Dmitry Pupinin (dlnsk at ngs dot ru).

This version is not compatible with the old Human Logic code.

This module and block may be distributed under the terms of the General Public License
(see http://www.gnu.org/licenses/gpl.txt for details)

-----------
PURPOSE
-----------
The attendance module is designed to allow instructors of a course keep an attendance log of the students in their courses.
The instructor will setup the frequency of his classes (# of days per week & length of course) and the attendance block
is ready for use. To take attendance, the instructor clicks on the "Update Attendance" button and is presented with a list
of all the students in that course, along with 4 options: Present, Absent, Late & Excused, with a Remarks textbox.
Instructors can download the attendance for their course in Excel format or text format. Only the instructor can update
the attendance data. However, a student gets to see his attendance record.

----------------
INSTALLATION
----------------
The attendance follows standard installation procedures. Place the "attendance" directory in your blocks directory,
"attendance" directory in your mod directory. Please delete old language files from your moodledata/lang/en directory
if you are upgrading the module. Then visit the Admin page in Moodle to activate it.

--------------
QUESTIONS ?
--------------

Q: Why attendance percent on pages of block don't equal to percent in Gradebook?
A: Gradebook display how much points student had in this course in comparison with how much he could have. Pages of block
display percent of attendance. For example, if student for presence had 3 points and for absence had 1 point then he can't
have 0% in Gradebook even though he was absent always, but on block pages in this situation he will have exactly 0%.

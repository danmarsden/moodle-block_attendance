<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

class block_attendance extends block_base {
	
    function init() {
        $this->title = get_string('blockname', 'block_attendance');
    }
    
	function get_content() {
		global $CFG, $USER, $COURSE;

		if ($this->content !== NULL) {
			return $this->content;
		}
		
        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = '';
		
		$attendances = get_all_instances_in_course('attforblock', $COURSE, NULL, true);
        if (count($attendances)==0) {
			 $this->content->text = get_string('needactivity', 'block_attendance');;
			 return $this->content;
		}
        
        require_once($CFG->dirroot.'/mod/attforblock/locallib.php');
        require_once($CFG->dirroot.'/mod/attforblock/renderhelpers.php');

        foreach ($attendances as $attinst) {
            $cmid = $attinst->coursemodule;
            $context = get_context_instance(CONTEXT_MODULE, $cmid, MUST_EXIST);
            $divided = $this->divide_databasetable_and_coursemodule_data($attinst);

            $att = new attforblock($divided->atttable, $divided->cm, $COURSE, $context);

            $this->content->text .= html_writer::link($att->url_view(), html_writer::tag('b', format_string($att->name)));
            $this->content->text .= html_writer::empty_tag('br');
            
            // link to attendance
            if ($att->perm->can_take() or $att->perm->can_change()) {
                $this->content->text .= html_writer::link($att->url_manage(array('from' => 'block')), get_string('takeattendance', 'attforblock'));
                $this->content->text .= html_writer::empty_tag('br');
            }
            if ($att->perm->can_manage()) {
                $url = $att->url_sessions(array('action' => att_sessions_page_params::ACTION_ADD));
                $this->content->text .= html_writer::link($url, get_string('add', 'attforblock'));
                $this->content->text .= html_writer::empty_tag('br');
            }
            if ($att->perm->can_view_reports()) {
                $this->content->text .= html_writer::link($att->url_report(), get_string('report', 'attforblock'));
                $this->content->text .= html_writer::empty_tag('br');
            }

            if ($att->perm->can_be_listed() && $att->perm->can_view()) {
                $this->content->text .= construct_full_user_stat_html_table($attinst, $COURSE, $USER);
            }
            $this->content->text .= "<br />";
        }
		return $this->content;
	}

    private function divide_databasetable_and_coursemodule_data($alldata) {
        static $cmfields;
        
        if (!isset($cmfields)) {
            $cmfields = array(
                    'coursemodule' => 'id',
                    'section' => 'section',
                    'visible' => 'visible',
                    'groupmode' => 'groupmode',
                    'groupingid' => 'groupingid',
                    'groupmembersonly' => 'groupmembersonly');
        }


        $atttable = new stdClass();
        $cm = new stdClass();
        foreach ($alldata as $field => $value) {
            if (array_key_exists($field, $cmfields)) $cm->{$cmfields[$field]} = $value;
            else $atttable->{$field} = $value;
        }

        $ret = new stdClass();
        $ret->atttable = $atttable;
        $ret->cm = $cm;

        return $ret;
    }
}
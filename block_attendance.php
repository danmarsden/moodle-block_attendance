<?PHP 
	
class block_attendance extends block_base {
	
    function init() {
        $this->title = get_string('blockname','block_attendance');
        $this->version = 2009022400;
        $this->release = '2.1.0';
    }
    
	function get_content() {
		global $CFG, $USER, $COURSE;

		if ($this->content !== NULL) {
			return $this->content;
		}
		
        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = '';
		
		if (!$att = array_pop(get_all_instances_in_course('attforblock', $COURSE, NULL, true))) {
			 $this->content->text = get_string('needactivity','block_attendance');;
			 return $this->content;
		}
		$cmid = $att->coursemodule;
		require_once($CFG->dirroot.'/mod/attforblock/locallib.php');
		
	    if (!$context = get_context_instance(CONTEXT_MODULE, $cmid)) {
	        print_error('badcontext');
	    }
	    
		// link to attendance
		if (has_capability('mod/attforblock:takeattendances', $context) or has_capability('mod/attforblock:changeattendances', $context)) {
			$this->content->text .= '<a href="'.$CFG->wwwroot.'/mod/attforblock/manage.php?id='.$cmid.'&amp;from=block">'.get_string('takeattendance','attforblock').'</a><br />';
		}
		if (has_capability('mod/attforblock:manageattendances', $context)) {
			$this->content->text .= '<a href="'.$CFG->wwwroot.'/mod/attforblock/sessions.php?id='.$cmid.'&amp;action=add">'.get_string('add','attforblock').'</a><br />';
		}
		if (has_capability('mod/attforblock:viewreports', $context)) {
			$this->content->text .= '<a href="'.$CFG->wwwroot.'/mod/attforblock/report.php?id='.$cmid.'&amp;view=weeks">'.get_string('report','attforblock').'</a><br />';
		}
		if (has_capability('mod/attforblock:export', $context)) {
			$this->content->text .= '<a href="'.$CFG->wwwroot.'/mod/attforblock/export.php?id='.$cmid.'">'.get_string('export','quiz').'</a><br />';
		}
		if (has_capability('mod/attforblock:changepreferences', $context)) {
			$this->content->text .= '<a href="'.$CFG->wwwroot.'/mod/attforblock/attsettings.php?id='.$cmid.'">'.get_string('settings','attforblock').'</a><br />';
		}
		
		if (isstudent($COURSE->id) && has_capability('mod/attforblock:view', $context)) {
			$complete = get_attendance($USER->id,$COURSE);
			if($complete == 0) {		//attendance not generated yet
				$this->content->text .= get_string('attendancenotstarted','attforblock');
			} else {					//attendance taken
            	$statuses = get_statuses($COURSE->id);
				foreach($statuses as $st) {
					$this->content->text .= $st->description.':&nbsp;'.get_attendance($USER->id,$COURSE,$st->id).'<br />';
				}
				$percent = get_percent($USER->id, $COURSE);
				$grade   = get_grade($USER->id, $COURSE);
				
				$this->content->text .= get_string('attendancepercent','attforblock').':&nbsp;'.$percent.'&nbsp;%<br />';
				$this->content->text .= get_string('attendancegrade','attforblock').":&nbsp;$grade<br />";

				$this->content->text .= '<a href="'.$CFG->wwwroot.'/mod/attforblock/view.php?id='.$cmid.'">'.get_string('indetail','attforblock').'</a>';
			}
		}
		
		return $this->content;
	}
}

?>

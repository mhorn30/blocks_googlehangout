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

/** Google Hangout Block
* A Moodle Block with a Google Hangout button
* @package: blocks
* @author: mhorn30
* @date: October 2014
* @country: South Africa
*/

//HOA: For Google Hangouts on Air comment in second $this->content->text and comment out first $this->content->text

class block_googlehangout extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_googlehangout');
    }

   function applicable_formats() {
        return array('all' => true);
    }

      function get_content() {
      	echo '<script type="text/javascript" src="https://apis.google.com/js/platform.js" async defer></script>';
        
	if ($this->content !== NULL) {
            return $this->content;
        }

	$this->content = new stdClass;
	$this->content->text = '<g:hangout render="createhangout"></g:hangout>';		
	//$this->content->text = '<g:hangout render="createhangout" hangout_type="onair"></g:hangout>';
  	$this->content->footer = '';
 
  	return $this->content;
    }


    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_googlehangout');
        return true;
    }

    function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        //find out if this block is on the profile page
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // this is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here
                return true;
            } else {
                // no JS on public personal pages, it would be a big security issue
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

}

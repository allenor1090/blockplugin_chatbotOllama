<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Block chatbot_ollama is defined here.
 *
 * @package     block_chatbot_ollama
 * @copyright   2024 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_chatbot_ollama extends block_base
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_chatbot_ollama');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {
        global $CFG, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $course = $this->page->course;
        $courseformat = course_get_format($course);
        $numsections = $courseformat->get_last_section_number();
        $context = context_course::instance($course->id);

        $roles = get_user_roles($context, $USER->id, true);
        $role = key($roles);
        $rolename = $roles[$role]->shortname;

        $data = array();
        array_push($data, $course->id);
        array_push($data, $rolename);

        // Suponiendo que $data contiene los datos que deseas pasar al iframe
        
        // Codificar el arreglo como JSON
        $json_data = json_encode($data);

        // Escapar caracteres especiales para usarlo en una URL
        $json_data_url = urlencode($json_data);
        
        $url = new moodle_url('http://localhost/chatbotOllama/index.php', array('parametro' => $json_data_url));

        $iframe_url = $url->out();


        // Generating HTML code for the iframe
        $iframe_html = '<iframe src="' . $iframe_url . '" width="100%" height="400px" frameborder="0"></iframe>';

        // Setting the block content to the iframe
        $this->content->text = $iframe_html;
        

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization()
    {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_chatbot_ollama');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config()
    {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats()
    {
        return array(
            'all' => false,
            'course-view' => true,
            'course-view-social' => false,
        );
    }
}

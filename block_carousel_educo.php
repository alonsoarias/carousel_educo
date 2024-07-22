<?php
global $CFG;
require_once($CFG->dirroot . '/theme/educo/inc/block_handler/get-content.php'); // Ajuste para el tema educo

class block_carousel_educo extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_carousel_educo');
    }

    public function specialization() {
        global $CFG;
        include($CFG->dirroot . '/theme/educo/inc/block_handler/specialization.php'); // Ajuste para el tema educo
        if (empty($this->config)) {
            $this->config = new stdClass();
            $this->config->itemsnumber = '1'; // Por defecto un solo slide
            $this->config->item_title1 = "Diapositiva 1 Título";
            $this->config->item_text1 = "Texto de la Diapositiva 1";
            $this->config->item_image1 = $CFG->wwwroot . '/theme/educo/pix/slide_country.jpg'; // Imagen por defecto
            $this->config->item_button1 = ""; // Botón opcional
            $this->config->item_link1 = ""; // Enlace opcional
        }
    }

    public function get_content() {
        global $CFG, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $itemsnumber = isset($this->config->itemsnumber) ? $this->config->itemsnumber : 1; // Por defecto un solo slide
        $itemsnumber = min(5, max(1, $itemsnumber)); // Asegurarse de que itemsnumber esté entre 1 y 5

        $slides = '';
        $contextid = $this->context->id;

        for ($i = 1; $i <= $itemsnumber; $i++) {
            $active = ($i == 1) ? 'active' : '';

            $image = $this->get_image_url($contextid, $i);
            if (empty($image)) {
                $image = $this->config->{"item_image$i"};
            }

            $button_html = '';
            if (!empty($this->config->{"item_button$i"})) {
                $link = !empty($this->config->{"item_link$i"}) ? $this->config->{"item_link$i"} : '#';
                $button_html = '<a href="' . $link . '" class="btn btn-primary" style="color: white;">' . $this->config->{"item_button$i"} . '</a>';
            }

            $slides .= '
            <div class="carousel-item ' . $active . '">
                <img src="' . $image . '" class="d-block w-100" alt="' . $this->config->{"item_title$i"} . '">
                <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.4);">
                    <h5 style="color: white;">' . $this->config->{"item_title$i"} . '</h5>
                    <p style="color: white;">' . $this->config->{"item_text$i"} . '</p>
                    ' . $button_html . '
                </div>
            </div>';
        }

        $this->content->text = '
        <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">' . $slides . '</div>
        </div>';

        $this->content->footer = '';
        return $this->content;
    }

    private function get_image_url($contextid, $itemid) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'block_carousel_educo', 'content', $itemid, 'sortorder', false);
        if (count($files) == 1) {
            $file = reset($files);
            return moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
        }
        return '';
    }

    function instance_allow_multiple() {
        return true;
    }

    function has_config() {
        return false;
    }

    function applicable_formats() {
        return array(
            'all' => true,
            'my' => false,
            'admin' => false,
            'course-view' => true,
            'course' => true,
        );
    }
}
?>

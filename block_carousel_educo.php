<?php

class block_carousel_educo extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_carousel_educo');
    }

    public function specialization() {
        global $CFG;
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
        $itemsnumber = isset($this->config->itemsnumber) ? $this->config->itemsnumber : 1;
        $itemsnumber = min(5, max(1, $itemsnumber));

        $slides = '';
        $contextid = $this->context->id;

        for ($i = 1; $i <= $itemsnumber; $i++) {
            $active = ($i == 1) ? 'active' : '';

            // Usa la función para obtener la URL de la imagen
            $image = $this->get_image_url($contextid, $i);
            if (empty($image)) {
                $image = $this->config->{"item_image$i"};
            }

            // Debugging: Check image URL
            if ($image) {
                $debug_message = "Image $i URL: $image";
            } else {
                $debug_message = "Image $i not found.";
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
            
            // Add debug message for each slide
            $slides .= '<div style="color: red; font-size: small;">' . $debug_message . '</div>';
        }

        $this->content->text = '
        <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">' . $slides . '</div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">' . get_string('previous', 'block_carousel_educo') . '</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">' . get_string('next', 'block_carousel_educo') . '</span>
            </button>
        </div>';

        $this->content->footer = '';
        return $this->content;
    }

    private function get_image_url($contextid, $itemid) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'block_carousel_educo', 'content', $itemid, 'sortorder', false);

        foreach ($files as $file) {
            if (!$file->is_directory()) {
                return moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
            }
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

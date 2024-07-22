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
            $this->config->itemsnumber = '3';
            for ($i = 1; $i <= 5; $i++) {
                $this->config->{"item_title$i"} = "Slide $i Title";
                $this->config->{"item_text$i"} = "Slide $i Text";
                $this->config->{"item_image$i"} = "";
            }
        }
    }

    public function get_content() {
        global $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $itemsnumber = isset($this->config->itemsnumber) ? $this->config->itemsnumber : 3;
        $itemsnumber = min(5, max(1, $itemsnumber)); // Ensure itemsnumber is between 1 and 5

        $indicators = '';
        $inner = '';
        for ($i = 1; $i <= $itemsnumber; $i++) {
            $active = ($i == 1) ? 'active' : '';
            $indicators .= '<button data-mdb-target="#carouselEduco" data-mdb-slide-to="' . ($i - 1) . '" class="' . $active . '" aria-label="Slide ' . $i . '"></button>';
            $inner .= '<div class="carousel-item ' . $active . '">
                          <img src="' . educo_block_image_process($this->config->{"item_image$i"}) . '" class="d-block w-100 img-fluid" alt="' . $this->config->{"item_title$i"} . '"/>
                          <div class="carousel-caption d-none d-md-block">
                              <h5>' . $this->config->{"item_title$i"} . '</h5>
                              <p>' . $this->config->{"item_text$i"} . '</p>
                          </div>
                       </div>';
        }

        $this->content->text = '
            <div id="carouselEduco" class="carousel slide carousel-fade carousel-dark" data-mdb-ride="carousel">
              <div class="carousel-indicators">' . $indicators . '</div>
              <div class="carousel-inner">' . $inner . '</div>
              <button class="carousel-control-prev" type="button" data-mdb-target="#carouselEduco" data-mdb-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-mdb-target="#carouselEduco" data-mdb-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>';

        $this->content->footer = '';
        return $this->content;
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

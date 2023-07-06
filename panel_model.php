<?php

// $GLOBALS xml reader and tables.
$xml = simplexml_load_file("reference.xml");
$table_28_1 = array();
$breakers = $xml->xpath("/tables/breaker/amps");
$fuses = $xml->xpath("/tables/fuse/amps");
ksort($breakers);
ksort($fuses);

class Component 
{
    protected const WIDTH  = 150;
    protected const HEIGHT = 45;
    protected const MARGIN = 5;

    protected $amps;
    protected $name;
    protected $x;
    protected $y;

    function __construct($name_init, $amps_init, $x_init, $y_init) {
        $this->name = $name_init;
        $this->amps = $amps_init;
        $this->x    = $x_init;
        $this->y    = $y_init;
    }

    public static function get_displacement_w() {
        return self::WIDTH + (self::MARGIN*2);
    }

    public static function get_displacement_h() {
        return self::HEIGHT + (self::MARGIN*2);
    }

    function set_name($name){
        $this->name = $name;
    }

    function get_amps(){
        return $this->amps;
    }
    function set_amps($amps){
        $this->amps = $amps;
    }

    function set_x($x) {
        $this->x = $x;
    }
    function get_x() {
        return $this->x;
    }
    function set_y($y) {
        $this->y = $y;
    }
    function get_y() {
        return $this->y;
    }
    function get_mid_x() {
        return $this->x + self::WIDTH/2;
    }
    function get_mid_y() {
        return $this->y + self::HEIGHT/2;
    }

    function print_svg(){
        // Generate SVG code to print out Component on the graph
        $w = self::WIDTH;
        $h = self::HEIGHT;
        $mid_x = $this->x + $w/2;
        $mid_y = $this->y + $h/2;
        $label1y = $mid_y - $this->get_displacement_h() / 6;
        $label2y = $mid_y + $this->get_displacement_h() / 6;
        $label = "$this->name";
        $label2 = "$this->amps A";

        $svg_rect = "<rect x='$this->x' y='$this->y' width='$w' height='$h' class='comp_box'/>";
        $svg_text = "<text x='$mid_x' y='$label1y' class='comp_label'>$label</text>";
        $svg_text2 = "<text x='$mid_x' y='$label2y' class='comp_label'>$label2</text>";
        $svg_input = "";
        if (isset($this->input)) {
            $svg_input.= $this->input->print_svg();
        }

        $svg = "";
        $svg.= $svg_input;
        $svg.= $svg_rect;
        $svg.= $svg_text;
        $svg.= $svg_text2;

        return $svg;
    }
}

class Wire 
{
    // awg is the text label. There are multiple for multiple lines.
    private $awg;
    private $awg2;
    private $awg3;
    private $pixel_thickness;
    private $from;
    private $to;

    function __construct($from_init, $to_init) {
        $this->from  = $from_init;
        $this->to    = $to_init;
    }

    function get_from() {
        return $this->from;
    }
    function get_to() {
        return $this->to;
    }

    function get_mid_x() {
        return ($this->from->get_mid_x() + $this->to->get_mid_x()) / 2;
    }
    function get_mid_y() {
        return (($this->from->get_y() + $this->from->get_displacement_h()) + $this->to->get_y()) / 2;
    }

    function update_awg() {
        // Search XML for awg value based on amps.
        $target_amps = max($this->from->get_amps(), $this->to->get_amps());
        foreach ($GLOBALS['table_28_1'] as $amp => $awg) {
            if ($amp >= $target_amps) {
                $this->awg = $awg;
                $this->awg2 = "";
                $this->awg3 = "";
                $this->pixel_thickness = ceil($amp / array_key_first($GLOBALS['table_28_1']));
                return;
            }
        }
        // No awg value found. Set the label to error text.
        $this->awg2 = "wire size exceeded.";
        if ($_SESSION["temp"] == "60C") {
            $this->awg = "60 deg. C max";
            $this->awg3 = "Try 75 deg. C.";
        } else {
            $this->awg = "75 deg. C max";
            $this->awg3 = "Wire too large.";
        }
    }
    
    protected function get_connection_svg() {
        $x1 = $this->from->get_mid_x();
        $y1 = $this->from->get_mid_y();
        $x2 = $this->to->get_mid_x();
        $y2 = $this->to->get_mid_y();
        return "<line x1='$x1' y1='$y1' x2='$x2' y2='$y2' stroke-width='$this->pixel_thickness' class='conn_line' />";
    }

    function print_svg(){
        $this->update_awg();
        $mid_x = $this->get_mid_x() + 18 + $this->pixel_thickness / 2;
        $mid_y = $this->get_mid_y();
        $mid_y -= Component::get_displacement_h() / 8;
        $label2y = $mid_y + Component::get_displacement_h() / 4;
        $label = "AWG";
        $label2 = $this->awg;
        
        // Normal text.
        $svg_text = "<text x='$mid_x' y='$mid_y' class='comp_label'>$label</text>";
        $svg_text2 = "<text x='$mid_x' y='$label2y' class='comp_label'>$label2</text>";
        $svg_text3 = "";
        $svg_text4 = "";
        $svg_input = $this->get_connection_svg();

        if ($this->awg2 != "") {
            // Error text.
            $label2 = "$this->awg";
            $label3 = "$this->awg2";
            $label4 = "$this->awg3";
            $mid_x = $this->get_mid_x();
            $mid_y -= Component::get_displacement_h() / 8 + Component::get_displacement_h() / 4;
            $label2y = $mid_y + Component::get_displacement_h() / 4;
            $label3y = $label2y + Component::get_displacement_h() / 4;
            $label4y = $label3y + Component::get_displacement_h() / 4;

            $svg_text = "<text x='$mid_x' style='fill:red' y='$mid_y' class='comp_label'>$label</text>";
            $svg_text2 = "<text x='$mid_x' style='fill:red' y='$label2y' class='comp_label'>$label2</text>";
            $svg_text3 = "<text x='$mid_x' style='fill:red' y='$label3y' class='comp_label'>$label3</text>";
            $svg_text4 = "<text x='$mid_x' style='fill:red' y='$label4y' class='comp_label'>$label4</text>";
        }

        $svg = "";
        $svg.= $svg_input;
        $svg.= $svg_text;
        $svg.= $svg_text2;
        $svg.= $svg_text3;
        $svg.= $svg_text4;

        return $svg;
    }
}

class MultipleOutputComponent extends Component {
    public $outputs = array();

    function print_svg(){
        // Override function specifically for MultipleOutputComponent display style.
        $w = self::WIDTH;
        $h = self::HEIGHT;
        $mid_x = $this->x + $w/2;
        $mid_y = $this->y + $h/2;
        $label = "$this->name";

        $svg_rect = "<rect x='$this->x' y='$this->y' width='$w' height='$h' class='comp_box'/>";
        $svg_text = "<text x='$mid_x' y='$mid_y' class='comp_label'>$label</text>";
        $svg_input = "";
        if (isset($this->input)) {
            $svg_input.= $this->input->print_svg();
        }

        $svg = "";
        $svg.= $svg_input;
        $svg.= $svg_rect;
        $svg.= $svg_text;

        return $svg;
    }
}

class Motor extends Component {
    public Wire $input;
    public $starter_type;

    private $horsepower;

    function __construct($name_init, $amps_init, $horsepower_init, $starter_init, $protection_init, $x_init, $y_init) {
        // Override constructor
        $this->name         = $name_init;
        $this->amps         = $amps_init;
        $this->starter_type = $starter_init;
        $this->horsepower   = $horsepower_init;
        $this->protection   = $protection_init;
        $this->x            = $x_init;
        $this->y            = $y_init;
    }

    function get_horsepower(){
        return $this->horsepower;
    }

    function get_protection(){
        return $this->protection;
    }

    public static function get_displacement_h() {
        // Override function
        return self::HEIGHT * 1.5 + (self::MARGIN*2);
    }

    function print_svg(){
        // Override function specifically for Motor display style.
        $w = self::WIDTH;
        $h = self::HEIGHT * 1.5;
        $mid_x = $this->x + $w/2;
        $mid_y = $this->y + $h/2;
        $label1y = $mid_y - $this->get_displacement_h() / 4.5;
        $label2y = $mid_y;
        $label3y = $mid_y + $this->get_displacement_h() / 4.5;
        $label = "$this->name";
        $label2 = "$this->amps A";
        $label3 = "$this->horsepower HP";

        $svg_rect = "<rect x='$this->x' y='$this->y' width='$w' height='$h' class='comp_box'/>";
        $svg_text = "<text x='$mid_x' y='$label1y' class='comp_label'>$label</text>";
        $svg_text2 = "<text x='$mid_x' y='$label2y' class='comp_label'>$label2</text>";
        $svg_text3 = "<text x='$mid_x' y='$label3y' class='comp_label'>$label3</text>";
        $svg_input = "";
        if (isset($this->input)) {
            $svg_input.= $this->input->print_svg();
        }

        $svg = "";
        $svg.= $svg_input;
        $svg.= $svg_rect;
        $svg.= $svg_text;
        $svg.= $svg_text2;
        $svg.= $svg_text3;

        return $svg;
    }
}

class Transformer extends Component {
    public Wire $input;
    public Wire $output;
}

class CircuitBreaker extends Component {
    public Wire $input;
    public Wire $output;
}

class Fuse extends Component {
    public Wire $input;
    public Wire $output;
}

class ThermalOverload extends Component {
    function __construct() { } // Override constructor 

    function print_svg(){
        // Override function specifically for ThermalOverload display style.
        $w = self::WIDTH;
        $h = self::HEIGHT;
        $mid_x = $this->x + $w/2;
        $mid_y = $this->y + $h/2;
        $label1y = $mid_y - $this->get_displacement_h() / 6;
        $label2y = $mid_y + $this->get_displacement_h() / 6;
        $label = "$this->name";
        $label2 = "$this->amps A";

        $svg_rect = "<rect x='$this->x' y='$this->y' width='$w' height='$h' class='comp_box'/>";
        $svg_text = "<text x='$mid_x' y='$label1y' class='comp_label'>$label</text>";
        $svg_text2 = "<text x='$mid_x' y='$label2y' class='comp_label'>$label2</text>";

        $svg = "";
        $svg.= $svg_rect;
        $svg.= $svg_text;
        $svg.= $svg_text2;

        return $svg;
    }
}

class Contactor extends Component {
    public Wire $input;
    public Wire $output;
    public ThermalOverload $thermal_overload;

    function __construct($name_init, $amps_init, $x_init, $y_init) {
        // Override constructor
        $this->name = $name_init;
        $this->amps = $amps_init;
        $this->x    = $x_init;
        $this->y    = $y_init;
        $this->thermal_overload = new ThermalOverload();
    }

    public static function get_displacement_h() {
        // Override function
        return self::HEIGHT * 2 + (self::MARGIN*2);
    }

    function setup_thermal_overload() {
        $this->thermal_overload->set_x($this->x);
        $this->thermal_overload->set_y($this->y + self::HEIGHT);
        $this->thermal_overload->set_name("Thermal Overload ".substr($this->name, -1));
    }

    function print_svg(){
        // Override function specifically for Contactor display style.
        $w = self::WIDTH;
        $h = self::HEIGHT * 2;
        $mid_x = $this->x + $w/2;
        $mid_y = $this->y + $h/2;
        $text_y = $mid_y - $h/4;
        $label1y = $text_y - $this->get_displacement_h() / 12;
        $label2y = $text_y + $this->get_displacement_h() / 12;
        $label = "$this->name";
        $label2 = "$this->amps A";

        $svg_rect = "<rect x='$this->x' y='$this->y' width='$w' height='$h' class='comp_box'/>";
        $svg_text = "<text x='$mid_x' y='$label1y' class='comp_label'>$label</text>";
        $svg_text2 = "<text x='$mid_x' y='$label2y' class='comp_label'>$label2</text>";
        $svg_input = "";
        if (isset($this->input)) {
            $svg_input.= $this->input->print_svg();
        }
        $svg_thermal_overload = $this->thermal_overload->print_svg();

        $svg = "";
        $svg.= $svg_input;
        $svg.= $svg_rect;
        $svg.= $svg_text;
        $svg.= $svg_text2;
        $svg.= $svg_thermal_overload;

        return $svg;
    }
}

class VariableFrequencyDrive extends Component {
    public Wire $input;
    public Wire $output;

    function print_svg(){
        // Override function specifically for VariableFrequencyDrive display style.
        $w = self::WIDTH;
        $h = self::HEIGHT;
        $mid_x = $this->x + $w/2;
        $mid_y = $this->y + $h/2;
        $label1y = $mid_y - $this->get_displacement_h() / 6;
        $label2y = $mid_y + $this->get_displacement_h() / 6;
        $label = "$this->name";
        $label2 = "$this->amps A";
        $label2 = $this->output->get_to()->get_horsepower() . " HP";

        $svg_rect = "<rect x='$this->x' y='$this->y' width='$w' height='$h' class='comp_box'/>";
        $svg_text = "<text x='$mid_x' y='$label1y' class='comp_label'>$label</text>";
        $svg_text2 = "<text x='$mid_x' y='$label2y' class='comp_label'>$label2</text>";
        $svg_input = "";
        if (isset($this->input)) {
            $svg_input.= $this->input->print_svg();
        }

        $svg = "";
        $svg.= $svg_input;
        $svg.= $svg_rect;
        $svg.= $svg_text;
        $svg.= $svg_text2;

        return $svg;
    }
}

class DistributionBlock extends MultipleOutputComponent {
    public Wire $input;
    // Change this to change the number of output ports on the distribution blocks
    const NUM_OUTPUTS = 5;

    public static function get_num_outputs() {
        return self::NUM_OUTPUTS;
    }
}


class Panel_Model {
    private $motors = array();
    private $end_x = 1;
    private $end_y = 1;
    
    private $component_rows = array();

    private function get_svg_width() {
        return count($this->motors) * Component::get_displacement_w();
    }

    private function get_svg_height() {
        return $this->end_y - Component::get_displacement_h() / 2;
    }

    private function get_svg_begin() {

        $svg_height = $this->get_svg_height();
        $svg_width  = $this->get_svg_width();

        return "<svg width='$svg_width' height='$svg_height'>";
    }

    private function get_svg_style() {
        return file_get_contents("svg_style.svg");
    }

    private function get_svg_end() {
        return "</svg>";
    }

    private function get_svg_components(&$components) {
        $svg = "";

        foreach ($components as $component) { 
            $svg.= $component->print_svg();
        }

        return $svg;
    }

    public function add_motor($name, $horsepower, $starter, $protection) {
        // Entry point of panel_model.php. All motor_form data comes through here.
        // Attempt to retrieve motor amp data from table_50_1. If no motor is found, set a session error and return true.
        $voltage = $_SESSION["voltage"];
        $phase = $_SESSION["phase"];
        $xpathstr = "/tables/table_50_1/voltage[@type='$voltage']/phase[@type='$phase']/amps[@hp='$horsepower']";
        $amps = $GLOBALS['xml']->xpath($xpathstr);
        if (empty($amps)) {
            $_SESSION['errors'][$name] = "
            Error: No motor exists for<br>
            Voltage: $voltage<br>
            Phase: $phase<br>
            Horsepower: $horsepower<br>";
            return true;
        }
        $amp = $amps[0];
        $new_motor = new Motor($name, floatval($amp), $horsepower, $starter, $protection, $this->end_x, $this->end_y);
        $this->motors[] = $new_motor;
        return false;
    }

    private function adjust_components_y() {
        // Set the y position neatly for all components.
        // Also setup contactor thermal overload appearance.
        $this->end_y = 0;
        for ($i = count($this->component_rows) - 1; $i>=0; $i--) {
            $row_height = 0;
            foreach ($this->component_rows[$i] as $component) {
                $component->set_y($this->end_y);
                $row_height = max($row_height, $component->get_displacement_h());
                if (get_class($component) == "Contactor") {
                    $component->setup_thermal_overload();
                }
            }
            $this->end_y += 1.2 * Component::get_displacement_h() + $row_height;
        }
    }

    private function make_new_component(&$next_obj, $class_name, $obj_name, $row_number) {
        // Generic function to create new component for code organization.
        $new_class_obj = new $class_name($obj_name, $next_obj->get_amps(), $this->end_x, $this->end_y);
        $new_wire = new Wire($new_class_obj, $next_obj);
        $new_class_obj->output = $new_wire;
        $next_obj->input = $new_wire;
        $this->component_rows[$row_number+1][] = $new_class_obj;
        $this->end_x += Component::get_displacement_w();
        return $new_class_obj;
    }

    public function generate_connecting_components() {
        if (empty($this->motors)) return;

        // Read in table_28_1; decipher and store in global variable.
        $ampobjects = $GLOBALS['xml']->xpath("/tables/table_28_1/temperature[@type='".$_SESSION['temp']."']/metal[@type='Copper']/amps");
        foreach ($ampobjects as $amp) {
            foreach($amp->attributes() as $awg) {
                $GLOBALS['table_28_1'][floatval($amp)] = $awg;
            }
        }
        ksort($GLOBALS['table_28_1']);

        // Group and sort the motors based on amp values, Fused / unfused, and starter type. This makes the proceeding algorithms work.
        $motor_counts = array();
        $contactor_i = 0;
        foreach ($this->motors as $motor) {
            $amps = $motor->get_amps();
            if (!array_key_exists($amps, $motor_counts)) {
                $motor_counts += array($amps => [$motor]);
            }
            else {
                if ($motor->get_protection() == "Fuse") {
                    array_push($motor_counts[$amps], $motor);
                } else {
                    if ($motor->starter_type == "VFD") {
                        array_splice($motor_counts[$amps], $contactor_i+1, 0, array($motor));
                    } else {
                        array_unshift($motor_counts[$amps], $motor);
                        $contactor_i++;
                    }
                }
            }
        }
        ksort($motor_counts);
        $rearranged_motors = [];
        foreach ($motor_counts as $motors) {
            foreach ($motors as $motor) {
                $motor->set_x($this->end_x);
                $this->end_x += Component::get_displacement_w();
                array_push($rearranged_motors, $motor);
            }
        }
        $this->motors = $rearranged_motors;  

        // Generate starters (VFD / Contactor)
        $row_number = 0;
        $this->end_x = 1;
        $this->component_rows[$row_number] = $this->motors;
        $vfd_number = 0;
        $contactor_number = 0;
        foreach ($this->component_rows[$row_number] as $motor) {
            if ($motor->starter_type == "VFD") {
                $this->make_new_component($motor, "VariableFrequencyDrive", "VFD ".++$vfd_number, $row_number);
            }
            else {
                $new_contactor = $this->make_new_component($motor, "Contactor", "Contactor ".++$contactor_number, $row_number);
                $new_contactor->set_amps(ceil($new_contactor->get_amps() / 10) * 10);
                $new_contactor->thermal_overload->set_amps($motor->get_amps() * 1.15);
            }
        }

        // Generate Distribution blocks. Store all new distribution blocks or individual components that weren't
        // assigned one in $row_composition for easy reference to all trailing components in the next step.
        $row_number++;
        $row_composition = [];
        $driver_index = 0;
        $db_number = 0;
        while ($driver_index < count($this->component_rows[$row_number])) {
            $fuse_group = [];
            $driver_group = [$this->component_rows[$row_number][$driver_index]];
            while (++$driver_index < count($this->component_rows[$row_number]) && end($driver_group)->get_amps() == $this->component_rows[$row_number][$driver_index]->get_amps()) {
                if ($this->component_rows[$row_number][$driver_index]->output->get_to()->get_protection() == "Fuse") {
                    array_push($fuse_group, $this->component_rows[$row_number][$driver_index]);
                } else {
                    array_push($driver_group, $this->component_rows[$row_number][$driver_index]);
                }
            }
            $needed_outputs = count($driver_group);
            if ($needed_outputs > 1) {
                $num_distribution_blocks = ceil($needed_outputs / DistributionBlock::get_num_outputs());
                $outputs_per_distribution = ceil($needed_outputs / $num_distribution_blocks);
                $outputs_connected = 0;
                $total_x = 0;
                foreach ($driver_group as $driver) {
                    $total_x += $driver->get_x();
                }
                $this->end_x = $total_x / $needed_outputs - ($num_distribution_blocks - 1) * Component::get_displacement_w() / 2;
                while (count($driver_group) > 0) {
                    $new_distribution_block = $this->make_new_component($driver_group[0], "DistributionBlock", "Distribution Block ".++$db_number, $row_number);
                    unset($new_distribution_block->output);
                    array_push($row_composition, $new_distribution_block);
                    for ($i = 0; $i < $outputs_per_distribution && $outputs_connected < $needed_outputs; $i++, $outputs_connected++) {
                        $driver = array_shift($driver_group);
                        $new_wire = new Wire($new_distribution_block, $driver);
                        $new_distribution_block->outputs[] = $new_wire;
                        unset($driver->input);
                        $driver->input = $new_wire;
                    }
                }
            }
            else {
                array_push($row_composition, ...$driver_group);
            }
            array_push($row_composition, ...$fuse_group);
        }
        
        // Pick up at $row_composition and generate protection (Circuit Breaker / Fuse)
        $row_number = count($this->component_rows) - 1;
        $cb_number = 0;
        $fuse_number = 0;
        foreach ($row_composition as $next_row_component) {
            $motor = $next_row_component;
            while (get_class($motor) != "Motor"){
                if (isset($motor->output)) {
                    $motor = $motor->output->get_to();
                }
                else {
                    $motor = $motor->outputs[0]->get_to();
                }
            }
            $target_amps = $motor->get_amps() * 2.5;
            if ($motor->get_protection() == "Fuse") {
                $new_fuse = $this->make_new_component($next_row_component, "Fuse", "Fuse  ".++$fuse_number, $row_number);
                $new_fuse->set_x($next_row_component->get_x());
                foreach ($GLOBALS['fuses'] as $amp) {
                    if ($amp >= $target_amps) {
                        $new_fuse->set_amps($amp);
                        break;
                    }
                }
            }
            else {
                $new_circuit_breaker = $this->make_new_component($next_row_component, "CircuitBreaker", "Circuit Breaker ".++$cb_number, $row_number);
                $new_circuit_breaker->set_x($next_row_component->get_x());
                foreach ($GLOBALS['breakers'] as $amp) {
                    if ($amp >= $target_amps) {
                        $new_circuit_breaker->set_amps($amp);
                        break;
                    }
                }
            }
        }

        // Set all the components' y positions.
        $this->adjust_components_y();
    }

    public function print_svg() {
        // Echo out the SVG code.
        echo("<font color=black>
            Selected Voltage: ".$_SESSION['voltage']."<br>
            Selected Phase: ".$_SESSION['phase']."<br>
            Selected Ambient Temperature: ".$_SESSION['temp']."<br><br>");
        
        $svg  = $this->get_svg_begin();
        $svg .= $this->get_svg_style();

        foreach ($this->component_rows as $components) {
            $svg .= $this->get_svg_components($components);
        }
        $svg .= $this->get_svg_end();
        return $svg;
    }
}

?>
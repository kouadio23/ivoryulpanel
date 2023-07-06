// functions I want to trigger
function count_input() {
    var f_panel = document.getElementById("f_panel");
    
    while (f_panel.hasChildNodes()) {
        f_panel.removeChild(f_panel.lastChild);
    }

    const MIN_MOTOR_CNT = 1;
    const MAX_MOTOR_CNT = 10;
    const MIN_POWER_CNT = 1;
    const MAX_POWER_CNT = 4;


    
    var motor_cnt = parseInt(document.getElementById('i_motor_cnt').value);    
    var power_cnt = parseInt(document.getElementById('i_power_cnt').value);

    if ((motor_cnt < MIN_MOTOR_CNT) || (motor_cnt > MAX_MOTOR_CNT)){
        alert("Number of motors must be between " 
            + MIN_MOTOR_CNT + " and " + MAX_MOTOR_CNT);
        return;
    }
    if ((power_cnt < MIN_POWER_CNT) || (power_cnt > MAX_POWER_CNT)){
        alert("Number of power sources must be between " 
            + MIN_POWER_CNT + " and " + MAX_POWER_CNT);
        return; 
    }



    var line_break = document.createElement("br");
    line_break = document.createElement("br");
    f_panel.appendChild(line_break);

    var header = document.createElement("h2");
    header.className = "input_title";
    header.textContent="How big are they?";
    f_panel.appendChild(header);    



    var power_title = document.createElement("div");
    power_title.className = "table_title";
    power_title.textContent ="Power Sources";
    f_panel.appendChild(power_title);

    var power_table = document.createElement("table");
    power_table.className = "count_input";

    for (i = MIN_POWER_CNT; i <= power_cnt; i++) {
        var power_tr = document.createElement("tr");
        var power_td_label = document.createElement("td");
        var power_td_input = document.createElement("td");

        var power_label = document.createTextNode("Power Source " + i+ " Amps")
        var power_input = document.createElement("input");
        power_input.type = "number";
        power_input.name = "power_source_" + i;
        power_input.className = "number_input";

        power_td_label.appendChild(power_label);
        power_td_input.appendChild(power_input);

        
        power_tr.appendChild(power_td_label);
        power_tr.appendChild(power_td_input);

        power_table.appendChild(power_tr);
    }
    f_panel.appendChild(power_table);

    line_break = document.createElement("br");
    f_panel.appendChild(line_break);


    var motor_title = document.createElement("div");
    motor_title.className = "table_title";
    motor_title.textContent ="Motors";

    f_panel.appendChild(motor_title);

    var motor_table = document.createElement("table");
    motor_table.className = "count_input";
    for (i = MIN_MOTOR_CNT; i <= motor_cnt; i++) {
        var motor_tr = document.createElement("tr");
        var motor_td_label = document.createElement("td");
        var motor_td_input = document.createElement("td");

        var motor_label = document.createTextNode("Motor " + i+ " Amps")
        var motor_input = document.createElement("input");
        motor_input.type = "number";
        motor_input.name = "motor_" + i;
        motor_input.className = "number_input";

        motor_td_label.appendChild(motor_label);
        motor_td_input.appendChild(motor_input);

        motor_tr.appendChild(motor_td_label);
        motor_tr.appendChild(motor_td_input);

        motor_table.appendChild(motor_tr);
    }
    f_panel.appendChild(motor_table);


    line_break = document.createElement("br");
    f_panel.appendChild(line_break);

    var submit_button = document.createElement("button");
    submit_button.textContent = "Submit";
    submit_button.type = "submit";
    submit_button.className = "main_btn"; 
    f_panel.appendChild(submit_button);
  }
  

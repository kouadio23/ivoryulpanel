export class CADDIEngine {

    // CAD Drafting Interface Engine (CADDIE)
    // CAD Design and Display Interface ENgine
    
    /*

        This class is managed by the RBDC team

        CAD Drafing Interface Engine, or CADDIE, is the canvas engine
        behind the panel builder interface. This handles the user navigating
        via middle click and dragging, as well as organizing, updating, and 
        running tests for components.

    */

    constructor (targetFPS) {

        // Set up canvas
        this.canvas = document.querySelector('.test-canvas');
        this.c = this.canvas.getContext('2d');
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;

        // Set up mouse tracking attributes
        this.middleClickPressed = false;
        this.curMouseX = 0;
        this.curMouseY = 0;
        this.lastMouseX = 0;
        this.lastMouseY = 0;
        this.mouseDX = 0;
        this.mouseDY = 0;

        // Performance attributes
        this.rollingFPS = 0;
        this.fps_target = targetFPS;
        this.fps = 0;

        // Zooming attributes        
        this.scale = 1;
        this.zoomOffsetX = 1;
        this.zoomOffsetY = 1;   
        this.mouseLastZoomX = 0;   
        this.mouseLastZoomY = 0;
        this.pauseForScroll = false;

        // Miscellenious attributes
        this.components = [];    
        this.pushed_buttons = {};  


        // Register needed listeners
        window.addEventListener("mousedown", this._mousedownHandler.bind(this));
        window.addEventListener("mouseup", this._mouseupHandler.bind(this));
        window.addEventListener("mousemove", this._mousemoveHandler.bind(this));
        window.addEventListener("wheel", this.zoom_handler.bind(this));
        window.addEventListener("keydown", this._buttonPressHandler.bind(this));
        window.addEventListener("keyup", this._buttonReleaseHandler.bind(this));
        

        // Register update task
        setInterval(this.generate_frame.bind(this), 1000/this.fps_target);
        setInterval(this.check_fps.bind(this), 500);

        this.generate_frame(); // First call so that the user doesn't have to wait for it to load
        
    }
    
    generate_frame() {

        /*

            This method is managed by the RBDC team

            This function generates and displays a new frame. This includes 
            processing user input, updating components, and drawing components 
            for the user.

        */

        this._updateMouseDeltas();

        // Update screen size based on window dimensions
        this.c.canvas.width = window.innerWidth;
        this.c.canvas.height = window.innerHeight;
        

        // Clear screen
        this.c.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        console.log("------------ NEW FRAME -----------")

        // Updates and then displays components 
        for (var comp of this.components){

            // Waits for scrolling to finish to prevent visual bugs
            while(this.pauseForScroll);

            // Drags the screen around if necessary
            if (this.middleClickPressed){
            
                /*
                    TODO:
                        This does not provide even movement at different
                        zoom levels. This means that the enviornment moves
                        faster than the mouse when you're zoomed in, but
                        slower than the mouse then you're zoomed out. This
                        isn't intuitive, and should be remidied
                */
                let totalMoveX = this.mouseDX * this.scale;
                let totalMoveY = this.mouseDY *  this.scale;

                comp.drag(totalMoveX, totalMoveY);
            }

            comp.draw(this.c, this.scale);
        }
        
        this.rollingFPS++;

    }

    add_component(newComponent){

        /*

            This method is managed by the RBDC team

            This method simply adds a new component for CADDIE
            to manage and display.

        */

        this.components.push(newComponent);
    }

    check_fps(){

        /*

            This method is managed by the RBDC team

            This method displays the FPS to the screen

        */

        this.fps = this.rollingFPS * 2;
        this.rollingFPS = 0;

        // Update the display FPS
        document.querySelector(".fps-counter").textContent = "FPS: " + this.fps + " (target: " + this.fps_target + ") | Zoom: " + this.scale + " | Num Objects: " + this.components.length;
    }

    get_object_at(x, y){

        let targetted_objects = [];

        // Loops through components and checks if the objects intersect
        let compTLX, compTLY, compBRX, compBRY; // TL = top left, BR = Bottom right
        for (let comp of this.components){

            compTLX = comp.relativeX + comp.offsetX;
            compTLY = comp.relativeY + comp.offsetY;
            compBRX = comp.relativeX + (comp.width * comp.scale) + comp.offsetX;
            compBRY = comp.relativeY + (comp.height * comp.scale) + comp.offsetY;

            if ( (compTLX <= x  && x<= compBRX) && (compTLY <= y && y <= compBRY) ){
                this.select_component(comp);
            } else {
                
                // Only deselect if the user isn't pressing ctrl
                if (!this.pushed_buttons['ctrl']){
                    comp.selected = false;
                }


            }

        }

    }

    select_component(comp){

        console.log("Selected component: ", comp.name);

        if (comp.selected){
            comp.selected = false;
        } else {
            comp.selected = true;
        }

    }

    zoom_handler(event){

        /*

            This method is managed by the RBDC team

            This method is the handler for scroll wheel events.
            This method changes the zoom and scale for the engine
            and each component

        */

        this.pauseForScroll = true;

        let scaleDelta = event.deltaY * 0.001;

        // Checks if this change is too much. If so, stop it
        if ((this.scale - scaleDelta <= 0.01)){
            this.pauseForScroll = false;
            return;
        }

        // Updates values
        this.scale -= scaleDelta;
        this.zoomOffsetX -= scaleDelta;
        this.zoomOffsetY -= scaleDelta; 
        this.mouseLastZoomX = this.curMouseX;
        this.mouseLastZoomY = this.curMouseY;

        // Update all components with the new zoom information
        for (var comp of this.components){
            comp.change_zoom(this.curMouseX, this.curMouseY, scaleDelta, this.scale);//event.deltaY * 0.001, this.scale);
        }

        this.pauseForScroll = false;
    }

    _updateMouseDeltas() {

        /*

            This method is managed by the RBDC team

            This method is the handler for mouse movements.
            It calculates the change in mouse position and 
            updates this.lastMouseX/Y attributes

        */
        
        this.mouseDX = this.curMouseX - this.lastMouseX; 
        this.mouseDY = this.curMouseY - this.lastMouseY;

        this.lastMouseX = this.curMouseX;
        this.lastMouseY = this.curMouseY;

    }

    _mousedownHandler(event) {

        /*

            This method is managed by the RBDC team

            This method is a handler for detecting if
            the mouse buttons were pressed.
            

        */

        if (event.button == 1 || event.buttons == 4) {
            // Checks if it was a middle click
            document.querySelector("body").style.cursor = "grabbing";
            this.middleClickPressed = 1;
        } else if (event.button == 0 || event.buttons == 4){
            // Checks if it was a left click
            let objects = this.get_object_at(event.clientX, event.clientY);
        }
    }

    _mouseupHandler (event) {

        /*

            This method is managed by the RBDC team

            This method is the handler to detect
            when the middle mouse button is released.

        */

        // Check if the middle button was released
        if (event.button == 1 || event.buttons == 4) {
            document.querySelector("body").style.cursor = "pointer";
            this.middleClickPressed = 0;
        }
    }

    _mousemoveHandler(event) {

        /*

            This method is managed by the RBDC team

            This method is the handler to detect when 
            the mouse is moved. This is tracked so that
            CADDIE can handle mouse inputs.

        */

        // update mouseX and mouseY based on user movement
        this.curMouseX = event.clientX;
        this.curMouseY = event.clientY;

    }

    _buttonPressHandler(event){

        if (event.which == 17) {
            this.pushed_buttons['ctrl'] = true;
            console.log("cntrl preesed");
        }

    }

    _buttonReleaseHandler(event){

        if (event.which == 17) {
            this.pushed_buttons['ctrl'] = false;
            console.log("cntrl released");
        }

    }

}

// DEPRECATED in favor of CADDIEngine
export class WorkstationEngine {
    // Class exposed to GPT-3 code-davinci-003 on 1/23/2023

    constructor (targetFPS) {

        throw new Error("WORKSTATION ENGINE IS DEPRECATED");

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


        


        // Register needed listeners
        window.addEventListener("mousedown", this._mousedownHandler.bind(this));
        window.addEventListener("mouseup", this._mouseupHandler.bind(this));
        window.addEventListener("mousemove", this._mousemoveHandler.bind(this));
        window.addEventListener("wheel", this.zoom_handler.bind(this));

        // this.canvas.onscroll = function (event){
        //     scale += event.deltaY * 0.1;
        //     console.log(scale);
        // }
        

        // Register update task
        setInterval(this.update.bind(this), 1000/this.fps_target);
        setInterval(this.check_fps.bind(this), 500);

        this.update(); // First call so that the user doesn't have to wait for it to load
        
    }
    
    update() {
        this._updateMouseDeltas();

        // Update screen size
        this.c.canvas.width = window.innerWidth;
        this.c.canvas.height = window.innerHeight;
        

        // Clear scree\
        this.c.clearRect(0, 0, this.canvas.width, this.canvas.height);

        let thisOffsetX = 1;
        let thisOffsetY = 1;
        

        // Updates and then displays components
        for (var comp of this.components){

            while(this.pauseForScroll);

            // Drags the screen around if necessary
            if (this.middleClickPressed){
                comp.drag(this.mouseDX * (.5 / this.scale), this.mouseDY * (.5 / this.scale)); // The (.5 / this.scale) helps sync it with the mouse
            }

            var compX = comp.x;
            var compY = comp.y;

            
            thisOffsetX = (comp.x - this.mouseLastZoomX) * this.scale + this.mouseLastZoomX;
            thisOffsetY = (comp.y - this.mouseLastZoomY) * this.scale + this.mouseLastZoomY;

            // console.log("offset: ", thisOffsetX, thisOffsetY);


            comp.draw(this.c, this.scale, thisOffsetX, thisOffsetY);
        }
        
        this.rollingFPS++;

    }

    add_component(newComponent){
        this.components.push(newComponent);
    }

    check_fps(){
        this.fps = this.rollingFPS * 2;
        this.rollingFPS = 0;

        // Update the display FPS
        document.querySelector(".fps-counter").textContent = "FPS: " + this.fps + " (target: " + this.fps_target + ") | Zoom: " + this.scale + " | Num Objects: " + this.components.length;
    }

    zoom_handler(event){
        this.pauseForScroll = true;

        if ((this.scale - event.deltaY * 0.001 <= 0)){
            // this.scale -= event.deltaY * 0.001;
            this.pauseForScroll = false;
            return;
        }

        this.scale -= event.deltaY * 0.001;


        console.log(this.scale);

        this.zoomOffsetX -= event.deltaY * 0.001;
        this.zoomOffsetY -= event.deltaY * 0.001;
        this.mouseLastZoomX = this.curMouseX;
        this.mouseLastZoomY = this.curMouseY;
        this.pauseForScroll = false;
    }

    _updateMouseDeltas() {
        
        this.mouseDX = this.curMouseX - this.lastMouseX; 
        this.mouseDY = this.curMouseY - this.lastMouseY;

        this.lastMouseX = this.curMouseX;
        this.lastMouseY = this.curMouseY;

    }

    _mousedownHandler(event) {
        // Checks if it was a middle click
        if (event.button == 1 || event.buttons == 4) {
            document.querySelector("body").style.cursor = "grabbing";
            this.middleClickPressed = 1;
        }
    }

    _mouseupHandler (event) {
        // Check if the middle button was released
        if (event.button == 1 || event.buttons == 4) {
            document.querySelector("body").style.cursor = "pointer";
            this.middleClickPressed = 0;
        }
    }

    _mousemoveHandler(event) {
        // update mouseX and mouseY based on user movement
        this.curMouseX = event.clientX;
        this.curMouseY = event.clientY;

    }
}

export class Component {

    /*

        This class represents a single component that can be displayed
        on the CADDIEngine. This was originally written by Joshua Kopaunik on
        1/31/23.

    */

    constructor(name, color="black", iconPath=null) {
        // Functionality 
        this.errors = [];
        this.children = [];
        this.parent = null; // start the parent as null
        this.personal_current_draw = 1;
        this.id = 0; // RBDC team will implement a UUID system later
        this.name = name;
        this.pass_up_map = {}; // Used for passing information up & down the tree


        // Attributes for the GUI interface.
        // These keep track of coordinate information
        // The RBDC team should be the only people using
        // or modifying these variables

        // Coordinate and location attributes
        this.absX = 200;
        this.absY = 200;
        this.relativeX = this.absX;
        this.relativeY = this.absY;
        this.offsetX = 0; // offset based on user position
        this.offsetY = 0; // offset based on user position

        // Location attributes for the middle
        this.absMiddleX = this.absX + (this.width / 2);
        this.absMiddleY = this.absY + (this.height / 2);
        this.relativeMiddleX = this.relativeX + (this.width / 2);
        this.relativeMiddleY = this.relativeY + (this.height / 2);

        // Configures image
        this.hasIcon = false;
        this.img;
        if (iconPath != null){
            this.hasIcon = true;
            this.img = new Image();
            this.img.src = iconPath;
        }


        // Misc attributes
        this.width = 100;
        this.height = 100;
        this.scale = 1;
        this.color = color;
        this.canvasContext;
        this.selected = false;


        // Call the first update
        this._update_middle_coords();
        
    }

    drag(deltaX, deltaY){

        /*

            This method is managed by RBDC

            This method moves the component around the canvas
            by changing its relative coordinates. This is used
            when the user is dragging the canvas around

        */

        this.relativeX += deltaX;
        this.relativeY += deltaY;

        this._update_middle_coords();
        
    }

    set_coords(newX=this.absX, newY=this.absY){

        /*

            This method is managed by RBDC

            This method is used to set the coords of this object.
            This includes the relative and absolute coordinates. 

        */

        // Calculate how far off the relative Coordinates are so we can account for them
        let relativeOffsetX = this.absX - this.relativeX; 
        let relativeOffsetY = this.absY - this.relativeY; 

        // Update the absolute coords
        this.absX = newX;
        this.absY = newY;

        // Update relative coords with new abs coords and relativeOffset
        this.relativeX = this.absX + relativeOffsetX;
        this.relativeY = this.absY + relativeOffsetY;


        // Make middle coords update
        this._update_middle_coords();
    }

    update_tree(){

        // Finds the top most parent
        if (this.parent == null){
            // this is the parent. Start recursing all the way down.
            this._update_recursive_down();

        } else {
            // this is not the grandparent. 
            this.parent.update_tree();
        }

        this.organize_children();
    }

    _update_recursive_down(){

        // this.total_current_draw = this.personal_current_draw;

        // Gets pass_up_map
        this.pass_up_map['total_current_draw'] = this.personal_current_draw;
        this.pass_up_map['widest_root'] = 0;
        this.pass_up_map['longest_root'] = 0;

        // Null map to hold data when looping
        let this_child_map = {};

        for (let child of this.children) {

            // Send update down & get updated result
            this_child_map = child._update_recursive_down();

            // Updates total current draw
            this.pass_up_map['total_current_draw'] += this_child_map['total_current_draw'];

            // Updates the widest root
            this.pass_up_map['widest_root'] += (this_child_map['widest_root'] - 1) <= 0 ? 0 : this_child_map['widest_root'] - 1; // ternary makes sure it isn't below 0
            this.pass_up_map['widest_root'] += 1; // increment for this child

            // Updates the deepest root

            if (this_child_map['longest_root'] + 1 > this.pass_up_map['longest_root']){
                this.pass_up_map['longest_root'] = this_child_map['longest_root'] + 1;
            }

        }

        // Add self to the longest_root value
        // this.pass_up_map['longest_root']++;

        this.update_self();
        // return this.total_current_draw;
        return this.pass_up_map;
    }

    organize_children(){

        /*

            This method is managed by RBDC

            This method arranges the children of this components
            to be neat. This means proper spacing.

        */

        let thisMaxRoot = this.pass_up_map['widest_root'];

        // Verifies that the max root is not 0
        // This can happen to components without descendants
        // since those components interpret no descendants as 
        // 0 wide
        if (thisMaxRoot < 1){
            thisMaxRoot = 1;
        }


        // Veriables that control positioning of component children
        let childWidth = 100;
        let childGap = 70;
        let totalChildWidth = childWidth + childGap + 100;
        let leftOffset = (childWidth * thisMaxRoot) / 2;
        let componentHeightY = 100;
        let componentSpaceY = 100;
        let totalComponentHeightY = componentHeightY + componentSpaceY;
        
        // Variables for the loop
        let thisChildIndex = 0;
        let childMaxRoot;
        let childAbsX;

        // Catch point for debugging breakpoints
        if (this.name == "comp3"){
            let x;
        }

        // Organizes the children
        if (this.children.length == 1){
            // There shouldn't be any offset if there's only one child. 
            // Thus, this is a special case for that

            let thisChild = this.children[0];
            thisChild.set_coords(this.absX, this.absY + totalComponentHeightY);
            thisChild.organize_children();

        } else if (this.children.length > 1) {

            // Since there is more than one child we need to do this loop
            for (var child of this.children){

                // Gets the wideness of this child
                childMaxRoot = child.pass_up_map['widest_root'];
                
                // Calculates the new absolute coordinates and sets them
                childAbsX =  this.absX - leftOffset + (thisChildIndex * totalChildWidth);
                child.set_coords(childAbsX, this.absY + totalComponentHeightY);

                // Update the index in regards to the total length of this child
                thisChildIndex += (childMaxRoot > 0) ? childMaxRoot : 1;

                // Recurse
                child.organize_children();

            }
        }
    }

    change_zoom(mouseX, mouseY, scaleDelta, scale){

        /*

            This method is managed by RBDC

            This method is used for changing zoom. Individual components need to 
            keep track of the zoom level as well as how to offset themselves. This
            is because individual components are in charge of keeping track of displaying
            themselves, not CADDIE.


        */
        
        this.offsetX -= (this.relativeMiddleX - mouseX) * scaleDelta ;
        this.offsetY -= (this.relativeMiddleY - mouseY) * scaleDelta ;

        this.scale = scale;
        
        this._update_middle_coords();
    }

    _update_middle_coords(){

        /*

            This method is managed by RBDC

            This method simply updates the middle coords based
            on the current corner coords.

        */
        
        this.absMiddleX = this.absX + (this.width / 2);
        this.absMiddleY = this.absY + (this.height / 2);

        this.relativeMiddleX = this.relativeX + (this.width / 2) * this.scale;
        this.relativeMiddleY = this.relativeY + (this.height / 2) * this.scale;

    }

    draw(canvasContext, scale){

        /*

            This method is managed by RBDC

            This method draws this object onto the canvasContext parameter,
            keeping in mind the scale. 

        */

        let thisFrameX = this.relativeX + this.offsetX;
        let thisFrameY = this.relativeY + this.offsetY;
        let thisFrameMidX = this.relativeMiddleX + this.offsetX;
        let thisFrameMidY = this.relativeMiddleY + this.offsetY;
        let thisFrameWidth = this.width;
        let thisFrameHeight = this.height;
        
        // Always restart the path before drawing
        canvasContext.beginPath();

        if (this.hasIcon){
            canvasContext.drawImage(this.img, thisFrameX, thisFrameY, thisFrameWidth * scale, thisFrameHeight * scale);
        } else {
            // set color
            canvasContext.fillStyle = this.color;

            // Draw the rectangle, as well as a red dot to indicate the center
            canvasContext.fillRect(thisFrameX, thisFrameY, thisFrameWidth * scale, thisFrameHeight * scale);
        }

        
    
        if (this.selected){
            canvasContext.strokeStyle = "white";
            canvasContext.strokeRect(thisFrameX, thisFrameY, thisFrameWidth * scale, thisFrameHeight * scale);
        }

        canvasContext.arc(thisFrameMidX, thisFrameMidY, 1, 0, 2 * Math.PI, false);
        canvasContext.fillStyle = "red";
        canvasContext.fill();
        canvasContext.fillStyle = "black";

        // Draws line to the parent
        if (this.parent != null){
            canvasContext.strokeStyle = "black";
            canvasContext.lineWidth = 2;
            canvasContext.beginPath();
            canvasContext.moveTo(this.relativeMiddleX + this.offsetX, this.relativeY + this.offsetY);
            canvasContext.lineTo(this.parent.relativeMiddleX + this.parent.offsetX, this.parent.relativeY + (this.parent.height * this.parent.scale) + this.parent.offsetY);
            canvasContext.stroke();
        }
        
    }
  
    update_self() {
      // must be implemented by child classes
      //   throw new Error("update_self() must be implemented by child classes");

      // Brother Clement's class will work on this
    }
  
    update_server() {
        // to be implemented by RBDC
        // This will send updated data back to the server for storage
        return false;
    }
  
    add_child(newComponent, perpetuate=true) {
        // Adds a child to the component
        // This will be used by the frontend to connect components together

        this.children.push(newComponent);
        if (perpetuate){
            newComponent.set_parent(this, false);
        }

        // Make sure everything updates to prevent visual bugs
        this.update_tree();
        this.organize_children();
    }
  
    set_parent(newComponent, perpetuate=true) {
        // Adds a parent to the component
        // This will be used by the frontend to connect components together

        this.parent = newComponent
        if (perpetuate){
            newComponent.add_child(this, false);
        }

        // Make sure everything updates to prevent visual bugs
        this.update_tree();
        this.organize_children();
    }

    get_data(){

        /*

            This Method will be meodified by all teams

            This function will return a JSON array containing all the data for this component.
            This function will be used by the frontend to display additional information,
            and it will also be used by this.update_server() to send updates to the server

        */

        // Get ID of all children
        let childIDArr = [];
        for (let child in this.children){
            childIDArr.push(child.id);
        }

        // Get the ID of parent, or use null if this is a top parent
        let parentId = null;
        if (this.parent != null){
            parentId = this.parent.id;
        }

        return {
            id: this.id,
            errors: this.errors,
            children: childIDArr,
            parent: parentId,
            personal_current: this.personal_current_draw,
            total_current: this.total_current_draw
        }
    }
  
    get_errors() {
      return this.errors;
    }

    get_total_current_draw() {
        return this.total_current_draw;
    }

    get_personal_current_draw() {
        return this.personal_current_draw;
    }

    get_children() {
        return this.children;
    }

    get_parent() {
        return this.parent;
    }
}
  
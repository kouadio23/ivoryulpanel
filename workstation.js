import { CADDIEngine } from "./js_objects/CADDIEngine.js";
import { Component } from "./js_objects/Component.js"

var engine = new CADDIEngine(30);


// drawExampleLayout1(); 
generateRandomPanel();


function generateRandomPanel(size=10){

    // This function randomly generates
    // a set amount of components and connects
    // them randomly. 

    let compArr = [];

    let firstComp = new Component("first comp", "black");

    compArr.push(firstComp);
    engine.add_component(firstComp);

    let name;
    let color;
    let parent;
    let newComp;
    let parentIndex;
    let newCompColor;

    for (let i=0; i < size - 1; i++){
        name = "Random Comp #" + i;
        color = "blue";

        newCompColor = "#" + Math.floor(Math.random()*16777215).toString(16);
        newComp = new Component(name, newCompColor);
        console.log(newCompColor);

        parentIndex = Math.floor(Math.random()*compArr.length);
        parent = compArr[parentIndex];
        parent.add_child(newComp);
        
        compArr.push(newComp);

        engine.add_component(newComp);
    }
   

}

function drawExampleLayout1(){

    let comp2 = new Component("comp2", "red");
    let comp1 = new Component("comp1", "blue");
    let comp3 = new Component("comp3", "purple");
    let comp4 = new Component("comp4", "green");
    let comp5 = new Component("comp5", "white");
    let comp6 = new Component("comp6");
    let comp7 = new Component("comp7", "yellow");
    let comp8 = new Component("comp8", "orange");
    let comp9 = new Component("comp9", "pink");
    let comp10 = new Component("comp10", "cyan");
    let comp11 = new Component("comp11", "gold");
    let comp12 = new Component("comp12", "#132482");
    let comp13 = new Component("comp13", "#13A402");



    // // Test Layout 2
    comp1.add_child(comp2);
    comp2.add_child(comp3);
    comp2.add_child(comp13);
    comp3.add_child(comp4);
    comp3.add_child(comp5);
    comp4.add_child(comp6);
    comp4.add_child(comp7);
    comp4.add_child(comp12);
    comp5.add_child(comp8);
    comp5.add_child(comp9);
    comp5.add_child(comp10);
    comp10.add_child(comp11);


    engine.add_component(comp1);
    engine.add_component(comp2);
    engine.add_component(comp3);
    engine.add_component(comp4);
    engine.add_component(comp5);
    engine.add_component(comp6);
    engine.add_component(comp7);
    engine.add_component(comp8);
    engine.add_component(comp9);
    engine.add_component(comp10);
    engine.add_component(comp11);
    engine.add_component(comp12);
    engine.add_component(comp13);

}
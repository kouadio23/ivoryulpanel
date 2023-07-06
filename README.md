# UL Panel App

## Description
This UL Panel App is to assist IBEX UL panel installers by generating the layout of their UL Panels. These generated diagrams are designed to follow the UL Standard 508A. With that being said, installers should still be certified themselves, and should doublecheck any output made by this application.

## Technologies

This application is built upon the three traditional frontend languages (HTML, CSS, and JS) as well as PHP and MySQL on the backend. Additionally, we are using GitLab as our central repo service, and InMotion for hosting the code. Finally, we are using XAMPP as our local development suite.

## Installation
Installation is fairly simple, and you should have recieved instructions from IBEX. Nonetheless, I will put a simple set up guide here. Contact your manager if you have issues.

1. Install XAMPP. You can do so [here](https://www.apachefriends.org/download.html). Use all the default values during installation.
2. Run XAMPP once you have it installed and start Apache. Then go to your web browser and navigate to localhost. You should see a generic website for XAMPP. 
3. Now we need to clone the repo. Go to `C:/xampp/htdocs` and delete everything in this directory. 
4. Next, open a terminal prompt in this directory. Use the following command: `git clone https://gitlab.com/razorbackusa/ul-panel-app.git .`

You should now be able to reload localhost (turn Apache back on if you turned it off) and see the UL Panel App. 


## Directory Contents
Below is the directory contents as well as explanations for important files, such as source code files
```
│   .gitignore          <-- List of files that git should ignore
│   .gitlab-ci.yml      <-- GitLab configuration file
│   .htaccess           <-- Specifies which page should be the landing page
│   generate_panel.php  <-- This page generates the panel design, and displays it to the user 
│   input.js            <-- I believe this handles changing inputs from the user
│   motor_form.php      <-- This screen takes information from the user regarding each motor
│   panel_form.php      <-- This is the landing page, and takes in the amount of motors and relevant information.
│   panel_model.php     <-- This holds all the class declerations for the project
│   README.md           <-- The file you are reading
│   redirect_test.php   <-- I believe this is an old test file -Josh
│   reference.xml       <-- This holds an XML table of UL 508A rules
│   style.css           <-- The stylesheet for the website
│   svg_style.svg       <-- The stylesheet for SVG objects on the website
│   test_form.php       <-- I believe this is an old test file -Josh
│   test_output.php     <-- I believe this is an old test file -Josh
│
├───cached_pages        <-- Directory that allows the server to hold cached diagrams, as well as a stylesheet
│       cached_pages_01.txt
│       style.css
│
└───images              <-- Directory to hold image assets
        color_scheme.PNG
        favicon.ico

```

## Usage
Usage for this application is for UL Panel Contractors in order to ease their process. Here is an example:

`
A farm wants to build a grain facility. It has several motors to drive mills, conveyors and grain elevators. They hire UL panel shop Ibex Controls to design the electrical panels for the site. They tell Ibex Controls the voltage and horsepower of each motor, for example 480 Volt, 100 Horsepower. Ibex Controls opens up the UL Panel App and enters the voltages and horsepower. The UL Panel app then applies the rules found in UL Standard 508A to calculate properly sized electrical components to safely power the motors. It displays a graph of all the required component sizes and how they should be connected. The UL Panel shop technician smiles when remembering the days of doing all of this by hand.
`

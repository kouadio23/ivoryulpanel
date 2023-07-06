# UL Panel App

## Description
This UL Panel App is to assist IBEX UL panel installers by generating the layout of their UL Panels. These generated diagrams are designed to follow the UL Standard 508A. With that being said, installers should still be certified themselves, and should doublecheck any output made by this application.

## Technologies

This application is built upon the three traditional frontend languages (HTML, CSS, and JS) as well as PHP and MariaDB on the backend. Additionally, we are using GitLab as our central repo service, and InMotion for hosting the code. Finally, we are using XAMPP as our local development suite.

## Installation
Installation is fairly simple, and you should have recieved instructions from IBEX. Nonetheless, I will put a simple set up guide here. Contact your manager if you have issues.

1. Install XAMPP. You can do so [here](https://www.apachefriends.org/download.html). Use all the default values during installation.
2. Run XAMPP once you have it installed and start Apache. Then go to your web browser and navigate to localhost. You should see a generic website for XAMPP. 
3. Now we need to clone the repo. Go to `C:/xampp/htdocs` and delete everything in this directory. 
4. Next, open a terminal prompt in this directory. Use the following command: `git clone https://github.com/ibex-controls/ul-panel-app/ .`

You should now be able to reload localhost (turn Apache back on if you turned it off) and see the UL Panel App. 


## Directory Contents
Below is the directory contents as well as explanations for important files, such as source code files
```

│
├─── .github/ <--- Contains github actions config files
│
├─── database_scripts/ <-- Holds scripts related to the database, such as original creation script
│
├─── Images/ <-- Holds Image assets
│ 
├─── Legacy_codebase/  <-- This holds the environment that the app originally had       
│
├─── Pages/ <-- Holds the PHP & HTML pages for the site
│       ├─── widgets/ <-- holds mini-widgets, such as the pop-out drawer
│       └─── dummy_data/ <-- holds files with temporary dummy data
│
├─── scripts/ <-- Holds Javascript files for the website
│       ├─── js_objects/ <-- holds the Class scripts for JS classes
│       └─── widget_scripts/ <-- holds scripts to enable page scripts, e.g. script for pop-out drawer
│
├─── styles/        <-- Directory to hold style.css
│
├─── .htaccess <-- Apache access file
├─── index.php <-- landing page for the site
└─── README.md <-- This file


```

## Usage
Usage for this application is for UL Panel Contractors in order to ease their process. Here is an example:

`
A farm wants to build a grain facility. It has several motors to drive mills, conveyors and grain elevators. They hire UL panel shop Ibex Controls to design the electrical panels for the site. They tell Ibex Controls the voltage and horsepower of each motor, for example 480 Volt, 100 Horsepower. Ibex Controls opens up the UL Panel App and enters the voltages and horsepower. The UL Panel app then applies the rules found in UL Standard 508A to calculate properly sized electrical components to safely power the motors. It displays a graph of all the required component sizes and how they should be connected. The UL Panel shop technician smiles when remembering the days of doing all of this by hand.
`

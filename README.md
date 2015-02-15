
#Photo Gallery
This was an exercise and exploration into Fuelphp. The original Photo Gallery project 
was a tutorial and introduction into more advanced OOP practices in PHP. 

I am not sure of the licensing, if any, to the lynda tutorial, so I did not include it 
in this project. It can be found at my website in the Resume > Deliverables section of my website
found at [Casperwilkes.net](http://casperwilkes.net).

##What is Photo Gallery?
Photo Gallery is an extremely simplified photo uploading application. The original purpose
of the project was to teach learning programmers several key features most applications will 
want:
- Upload files
- Create a login / logout system
- Create log files
- Read from files
- Write to files
- CRUD interaction with databases

##Purpose
The original project was photo_gallery, which is the reason for this project being named
photo_gallery_new. I did the original project when I was first learning OOP practices and
more advanced PHP using [Lynda.com](http://www.lynda.com) tutorials. 

This project is a revisit to the first. I wanted a deeper understanding of the Fuelphp 
framework so I used photo_gallery as a starting point. The front end looks 
exactly the same, while the back end has been completely made over. I will, more than
likely, revisit this application and provide follow ups to it in the near future. I 
have already started to branch out and try new ideas. 

##What's changed between the two projects?
The first project was not written with MVC in mind. There are a lot of includes and single page
modules. Also, it was written very simple to be functional; it's just a tutorial. The second
project took the key ideas of the first, and broke them up into the MVC architecture using the 
Fuelphp framework. The first application dealt with almost no security, whereas the second takes
security far more seriously, as it's integrated into the framework. The first application also 
uses the now deprecated mysql* functions. The new application utilizes an ORM. Again,
the two applications are vastly different, but use the same layout and key ideas. 

##To Use the Projects
I have included the migrations needed to set up the database. I have also provided the 
sql to start up the application. The sql for the database can be found in the sql directory 
at the base of the project.

##The Road Ahead
I am already working on and plan on implementing the following features:
- User profiles
- Multiple user uploads instead of just admin upload
- Group controls
- User emails
- More admin control
- A log reader (*I plan on turning this into a fuelphp package*)
- A much richer experience all around


###*Please Note*
- I do not own the images that were used in the tutorial, and do not claim them
- I included the images in the second project because they were used in the first
- The Fuelphp changelog, contributing, readme, and testing information have been 
moved to the fuel_info directory at the base of the project
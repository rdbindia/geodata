#Project – Geo Data 

Project Approach  

- We wanted to create a heatmap inside a bounding box that was either static or defined by the user. 
  - The requirements that were used were
    -   Laravel 
    - Leaflet JS 
    - Heroku
    - GitHub 
    - GeoLite 
  - Steps Taken 
    - A bottom up approach was taken for building the stepping stones of this project.
    - The first step was to learn how the Leaflet.js works and implement the examples to understand it better.
    - The JSON file to create this heatmap was studied to create a heatmap on the map. 
    - To create this JSON, Laravel was used to create an API endpoint that would display all the given co-ordinates in the Geolite CSV file. This file had to be optimized for better performance. 
    - This was handled in Laravel by reading the CSV file and displaying the generated output in the form of JSON containing the latitude, longitude and the count in that location. This count specifies the intensity of the heat in that area. Red being the highest intensity and blue being the lower intensity. Eg : {"data":[{"latitude":"32.9657","longitude":"-96.8825","count":1},{"latitude":"32.9657","longitude":"-96.8825","count":1},{"latitude":"32.9657","longitude":"-96.8825","count":1}]} 
    - The page on load that is seen currently on the project is the actual points from the csv file that is generated on the map in the beginning. 
    - The next step was to create a bounding box on the map and filtering the points that lie inside this bounding box. 
    - We tried using static points at first and creating a bounding box to plot points on the map.  
      Eg : var polygon = L.polygon([  [51.509, -0.08],  [51.503, -0.06],  [51.51, -0.047] ]).addTo(mymap); 
    - To find the points inside this bounding box the following logic was applied: 
      - The listed latitudes and longitudes were calculated and checked if they were in between any of the input bounding points selected by the user. 
      - If the listed point lies in between any of the bounding points an array of these latitudes and longitudes were made which would then determine the list of new co-ordinates on the map that are inside the bounding box. 
      - This array created was then converted into a JSON format to determine the new points. 
    - After getting the points in place using a static user inputs for the bounding box. The goal had to be switched to make this bounding box to be a dynamic one.
    - Using the Leaflet JS, a dynamic polygon tool was implemented and co-ordinates were traced back using the following: map.on('draw:created', function (e) { var layer = e.layer; var shape = layer.toGeoJSON() }); 
    - These co-ordinates were then posted using the AJAX request and calculated, and a valid JSON was passed to create a heatmap on the map. 

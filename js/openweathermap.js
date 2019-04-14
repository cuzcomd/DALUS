$(document).ready(function() {
    $.getJSON("https://api.openweathermap.org/data/2.5/weather?q="+ cityName +"&appid=" + OWMAPIkey +"&units=metric&lang=de", function(data) {
        
        let city = data["name"];
        let temp = data["main"]["temp"];
        let wind_direction = data["wind"]["deg"];
        let wind_speed = data["wind"]["speed"];
        let clouds = data["clouds"]["all"];
        let wetter = data["weather"][0]["description"];
        
        $(".weathercity").html(city +" : " + wetter);
        $(".temp").html("<i class='fa fa-thermometer-half'></i> "+Math.round(temp) + " °C");
        $(".wind-direction").html("<i class='fa fa-compass'></i> " +Math.round(wind_direction) + " °");
        document.getElementById('arrow').style.transform = 'rotate('+(Math.round(wind_direction)-90)+'deg)';
        document.getElementById("windrichtung").value = Math.round(wind_direction);
        $(".wind-speed").html("<i class='fa fa-tachometer'></i> "+Math.round(wind_speed) + " m/s");
         $(".clouds").html("<i class='fa fa-cloud'></i> "+Math.round(clouds) + " %");
      })
});

function getMETweather(){ //Bestimmt die Wetterdaten für das MET-Modell

    $.getJSON("https://api.openweathermap.org/data/2.5/weather?q="+ cityName +"&appid=" + OWMAPIkey +"&units=metric&lang=de", function(data) {
        let temp = data["main"]["temp"];
        let wind_dir = data["wind"]["deg"];
        let wind_speed = data["wind"]["speed"];
        
        if (wind_speed < 1) var wind = "low";
        if (wind_speed >=1 && wind_speed <5) var wind = "medium";
        if (wind_speed >=5) var wind = "high";

        let clouds = (data["clouds"]["all"] < 51) ? false : true;
        let fog = (data["weather"][0]["id"] == "741") ? true : false;
        let daytime = (data["dt"] > data["sys"]["sunrise"] && data["dt"] < data["sys"]["sunset"]) ? "day" : "night";
        let month = (new Date().getMonth() > 8 || new Date().getMonth() < 4) ? "om" : "as";

        document.getElementById("nebel").value = fog;
        document.getElementById("windgeschwindigkeit").value = wind;
        document.getElementById("himmel").value = clouds;
        document.getElementById("tageszeit").value = daytime;
        document.getElementById("monat").value = month;
    });
}

if (localStorage.when != null
    && parseInt(localStorage.when) + 300000 > Date.now()) // checking the localstorage  data acess time within 5 minutes interval

{
    let freshness = Math.round((Date.now() - localStorage.when) / 1000) + " second(s) ago";
    console.log(freshness)
    // creating variable for using in html where id = display
    var weather = ` 
<h3 style="color: orange; font-style: oblique; font-variant: small-caps;"> Last Updated time::${freshness} </h3>

<h2 > City Name = ${localStorage.mycity} , ${localStorage.mycountry}</h2>
<h4 >Main Weather Condition = ${localStorage.myWeather}</h4>
<p > Temperature = ${localStorage.myTemperature}°C</p>
<p> Pressure = ${localStorage.pressure} hpa</p>
<p> Humidity = ${localStorage.humidity}%</p>
<p > Wind Speed  = ${localStorage.wind} m/s </p>
<p> Direction = ${localStorage.direction}°</p>
`
    document.getElementById("display").innerHTML = weather;

    // No local cache, access network
} else 
{
    // Fetch weather data from API for given city
    fetch('http://localhost:8002/index.php')
        // Convert response string to json object
        .then(response => response.json())
        .then(data => {
            console.log(data.weather_when)
            console.log(data.city)
            console.log(data.weather_description)
            console.log(data.pressure)
            console.log(data.humidity)

            const celcius = Math.round(data.weather_temperature - 273);
            console.log(celcius)


            // Save new data to browser, with new timestamp
            localStorage.myWeather = data.weather_description;
            localStorage.myTemperature = celcius;
            localStorage.mycity = data.city;
            localStorage.mycountry = data.country;
            localStorage.pressure = data.pressure;
            localStorage.humidity = data.humidity;
            localStorage.wind = data.weather_wind;
            localStorage.direction = data.direction;
            localStorage.when = Date.now(); // milliseconds since January 1 1970
        })
        .catch(err => {
            // Display errors in console
            console.log(err);
        });
}
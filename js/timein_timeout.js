function formatTime(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var ampm = hours >= 12 ? "PM" : "AM";
  
    hours = hours % 12;
    hours = hours ? hours : 12;
    hours = hours.toString().padStart(2, "0");
    minutes = minutes.toString().padStart(2, "0");
    seconds = seconds.toString().padStart(2, "0");
  
    return hours + ":" + minutes + ":" + seconds + " " + ampm;
  }
  
  function formatDate(date) {
    const options = { month: "long", day: "numeric", year: "numeric" };
    return date.toLocaleDateString(undefined, options);
  }
  
  function updateDateTime() {
    var date = new Date();
    var currentTime = formatTime(date);
    var currentDate = formatDate(date);
    document.getElementById("live-time").textContent =
      "Current Time and Date: " + currentTime + " " + currentDate;
  }
  
  setInterval(updateDateTime, 1000);
  
  function formatTableDate(date) {
    const options = { month: "long", day: "numeric", year: "numeric" };
    return date.toLocaleDateString(undefined, options);
  }
  
  function updateTableDate() {
    var date = new Date();
    var currentDate = formatTableDate(date);
    var dateElements = document.getElementsByClassName("date");
    for (var i = 0; i < dateElements.length; i++) {
      dateElements[i].textContent = currentDate;
    }
  }
  
  setInterval(updateTableDate, 1000);
  
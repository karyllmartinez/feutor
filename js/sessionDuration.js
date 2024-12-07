// function calculateDuration() {
//     // Get the start time and end time values
//     const startTime = document.getElementById("startTime").value;
//     const endTime = document.getElementById("endTime").value;

//     // Convert the time strings to Date objects
//     const startDate = new Date("1970-01-01T" + startTime + "Z");
//     const endDate = new Date("1970-01-01T" + endTime + "Z");

//     // Calculate the duration in milliseconds
//     let duration = endDate - startDate;

//     // Check if the duration is negative (end time before start time)
//     if (duration < 0) {
//         alert("End time should be after start time.");
//         return; // Exit the function
//     }

//     // Convert duration to hours
//     duration = duration / (1000 * 60 * 60);

//     // Update the duration input field value
//     document.getElementById("duration").value = duration.toFixed(1);
// }

//   function calculateDuration() {
//     const startTimeInput = document.getElementById("startTime");
//     const endTimeInput = document.getElementById("endTime");
//     const durationInput = document.getElementById("duration");

//     const startTime = startTimeInput.value;
//     const endTime = endTimeInput.value;

//     if (!startTime || !endTime) {
//       durationInput.value = "";
//       return;
//     }

//     // Parse the time values to Date objects for comparison
//     const [startHours, startMinutes] = startTime.split(":").map(Number);
//     const [endHours, endMinutes] = endTime.split(":").map(Number);

//     const startDate = new Date();
//     startDate.setHours(startHours, startMinutes);

//     const endDate = new Date();
//     endDate.setHours(endHours, endMinutes);

//     // Check if end time is earlier than start time (for overnight sessions)
//     if (endDate < startDate) {
//       endDate.setDate(endDate.getDate() + 1); // add a day to endDate
//     }

//     // Calculate the duration in hours
//     const duration = (endDate - startDate) / (1000 * 60 * 60); // milliseconds to hours

//     if (duration >= 0) {
//       durationInput.value = duration.toFixed(2); // Show duration in hours with two decimals
//     } else {
//       durationInput.value = "0";
//     }
//   }



//   function calculateDuration() {
//     const startTime = document.getElementById("startTime").value;
//     const endTime = document.getElementById("endTime").value;
    
//     if (startTime && endTime) {
//       // Convert to Date objects for calculations
//       const start = new Date(`1970-01-01T${startTime}:00`);
//       const end = new Date(`1970-01-01T${endTime}:00`);

//       // Handle case where end time is past midnight (next day)
//       if (end < start) {
//         end.setDate(end.getDate() + 1);
//       }

//       // Calculate the duration in hours
//       const duration = (end - start) / (1000 * 60 * 60);
//       document.getElementById("duration").value = duration.toFixed(2);
//     }
//   }

    function calculateDuration() {
        // Get the start and end times from the input fields
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

        // Only proceed if both start and end times are provided
        if (startTime && endTime) {
            // Convert times to Date objects with a fixed date to avoid timezone issues
            const startDate = new Date("1970-01-01T" + startTime + "Z");
            const endDate = new Date("1970-01-01T" + endTime + "Z");

            // Calculate the duration in milliseconds
            let durationInMs = endDate - startDate;

            // If the end time is before the start time, it means it spans to the next day
            if (durationInMs < 0) {
                durationInMs += 24 * 60 * 60 * 1000; // Add 24 hours (in milliseconds)
            }

            // Convert the duration to hours
            const durationInHours = (durationInMs / 1000 / 60 / 60).toFixed(2);

            // Display the calculated duration in the duration input field
            document.getElementById('duration').value = durationInHours;
        } else {
            document.getElementById('duration').value = ''; // Clear duration if input is incomplete
        }
    }





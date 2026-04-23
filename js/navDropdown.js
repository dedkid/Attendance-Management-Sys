
        // Get the switch element
        const switchElement = document.getElementById('switch');

        // Get the dropdown container
        const dropdownContainer = document.querySelector('.dropdown');

		// Get the switch element
        const switch_mngmts = document.getElementById('switch_mngmt');

        // Get the dropdown container
        const dropdown_mngmts = document.querySelector('.dropdown_mngmt');

          // Get the switch element
          const switch_csks = document.getElementById('switch_csk');

          // Get the dropdown container
          const dropdown_csks = document.querySelector('.dropdown_csk');


        // Add click event listener to the switch
        switchElement.addEventListener('click', function () {
            // Toggle the display of the dropdown container
            dropdownContainer.style.display = (dropdownContainer.style.display === 'none') ? 'block' : 'none';
        });

        // Add click event listener to the switch
        switch_mngmts.addEventListener('click', function () {
            // Toggle the display of the dropdown container
		dropdown_mngmts.style.display = (dropdown_mngmts.style.display === 'none') ? 'block' : 'none';
        });

         // Add click event listener to the switch
         switch_csks.addEventListener('click', function () {
            // Toggle the display of the dropdown container
		dropdown_csks.style.display = (dropdown_csks.style.display === 'none') ? 'block' : 'none';
        });
		
		
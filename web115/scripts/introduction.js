function addCourse() {
  const newCourseInput = document.getElementById('new_course_input');
  const courseValue = newCourseInput.value.trim();
  
  if (courseValue) {
    const courseListDiv = document.getElementById('course-list');
    
    const newCourseGroup = document.createElement('div');
    newCourseGroup.className = 'course-item-group';
    
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.className = 'course-input'; 
    newInput.value = courseValue;
    newInput.required = true; 
    newInput.setAttribute('placeholder', 'e.g., HUM120'); 

    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'remove-course-btn';
    removeButton.textContent = 'Remove';
    removeButton.setAttribute('onclick', 'removeCourse(this)'); 
    
    newCourseGroup.appendChild(newInput);
    newCourseGroup.appendChild(removeButton);
    
    courseListDiv.appendChild(newCourseGroup);
    
    newCourseInput.value = '';
  } else {
    alert("Please enter a course name before adding."); 
  }
}

function removeCourse(buttonElement) {
  const courseItemGroup = buttonElement.closest('.course-item-group');
  if (courseItemGroup) {
    courseItemGroup.remove();
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('introForm');
  if (form) {
    form.addEventListener('submit', function(event) {
      event.preventDefault(); 
      
      const name = document.getElementById('name').value.trim();
      const statement = document.getElementById('statement').value.trim();
      const interest1 = document.getElementById('interest_1').value.trim();
      const interest2 = document.getElementById('interest_2').value.trim();
      const interest3 = document.getElementById('interest_3').value.trim();
      
      const courseInputs = document.querySelectorAll('#course-list .course-input');
      const courses = [];
      courseInputs.forEach(input => {
        if (input.value.trim()) {
          courses.push(input.value.trim());
        }
      });

      let outputHTML = '';
      
      outputHTML += '<hr>';
      outputHTML += `<img src="images/pfp.jpg" alt="${name}'s profile picture">`;
      outputHTML += `<p>${statement.replace('Sal', name)}</p>`;
      
      outputHTML += '<ul>';

      outputHTML += `
        <li>My interests includes ${interest1}, ${interest2}, and a bit of ${interest3}.</li>
      `;

      if (courses.length > 0) {
        outputHTML += `
          <li>I am currently taking the following courses:
            <ol>
        `;
        courses.forEach(course => {
          outputHTML += `<li>${course}</li>`;
        });
        outputHTML += `
            </ol>
          </li>
        `;
      }
      
      outputHTML += '</ul>';

      const outputDiv = document.getElementById('form-output-area');
      const originalDiv = document.getElementById('original-content');
      
      outputDiv.innerHTML = '<h3>Generated Introduction Page Preview</h3>' + outputHTML;
      
      outputDiv.style.display = 'block';
      originalDiv.style.display = 'none';
    });
  }
});
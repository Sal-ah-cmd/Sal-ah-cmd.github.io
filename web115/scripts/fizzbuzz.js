document.getElementById('nameForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const firstName = document.getElementById('first_name').value.trim();
  const middleInitial = document.getElementById('middle_initial').value.trim();
  const lastName = document.getElementById('last_name').value.trim();
  const greetingElement = document.getElementById('greeting');
  const outputDiv = document.getElementById('fizzbuzz-output');
  
  outputDiv.innerHTML = '';

  if (!firstName) {
    alert("Please enter at least your first name.");
    return;
  }

  let fullName = firstName;
  if (middleInitial) {
    fullName += ` ${middleInitial}.`;
  }
  if (lastName) {
    fullName += ` ${lastName}`;
  }
  
  greetingElement.textContent = `Welcome to Sal's Sneaky Snake, ${fullName}!`;
  
  const limitInput = prompt(`How high do you want to count, ${firstName}?`);
  let limit = parseInt(limitInput, 10);

  if (isNaN(limit) || limit <= 0) {
    alert("Invalid input. Please enter a positive number.");
    return;
  }
  
let outputHTML = '<ul>';
 for (let i = 1; i <= limit; i++) {
   let parity = (i % 2 === 0) ? 'even' : 'odd';
   let output = `Sneaky Snake - The number is ${parity}`;
   outputHTML += `<li>${i}) ${output}</li>`;
 }
 outputHTML += '</ul>';

 outputDiv.innerHTML = outputHTML;
});
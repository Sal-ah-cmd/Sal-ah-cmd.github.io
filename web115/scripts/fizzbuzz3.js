function checkDivision(num1, num2) {
  return num1 % num2 === 0;
}

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

  const config = {
    limit: limit,
    divisors: {
      3: { word: 'Sneaky', class: 'fizz' },
      5: { word: 'Snake', class: 'buzz' },
      7: { word: 'BANG', class: 'bang' }
    },
    defaultPhrase: 'Sss...'
  };

  let outputHTML = '<ul>';

  for (let i = 1; i <= config.limit; i++) {
    let result = '';
    let cssClasses = [];
    let isDivisible = false;

    for (const divisor in config.divisors) {
      if (checkDivision(i, Number(divisor))) {   
        result += config.divisors[divisor].word + ' ';
        cssClasses.push(config.divisors[divisor].class);
        isDivisible = true;
      }
    }

    if (!isDivisible) {
      result = i;
      cssClasses.push('default'); 
    }
    
    result = result.toString().trim();
    const finalClass = cssClasses.join(' ');

    outputHTML += `<li class="fizzbuzz-item ${finalClass}">
                     <span class="item-number">${i}</span> ${result}
                   </li>`;
  }

  outputHTML += '</ul>';
  outputDiv.innerHTML = outputHTML;
});

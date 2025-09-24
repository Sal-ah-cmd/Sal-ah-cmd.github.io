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

  const firstDivisor = 3;
  const secondDivisor = 5;

  const config = {
    limit: limit,
    divisors: {
      [firstDivisor]: { word: 'Sneaky', class: 'fizz' },
      [secondDivisor]: { word: 'Snake', class: 'buzz' }
    },
    defaultPhrase: 'Sss...'
  };

  let outputHTML = '<ul>';

  for (let i = 1; i <= config.limit; i++) {
    let result = '';
    let cssClass = '';

    if (checkDivision(i, firstDivisor) && checkDivision(i, secondDivisor)) {
        result = `${config.divisors[firstDivisor].word} ${config.divisors[secondDivisor].word}`;
        cssClass = 'fizzbuzz';
    } else if (checkDivision(i, firstDivisor)) {
        result = config.divisors[firstDivisor].word;
        cssClass = config.divisors[firstDivisor].class;
    } else if (checkDivision(i, secondDivisor)) {
        result = config.divisors[secondDivisor].word;
        cssClass = config.divisors[secondDivisor].class;
    } else {
        result = i;
        cssClass = config.defaultPhrase;
    }
    outputHTML += `<li class="fizzbuzz-item ${cssClass}"><span class="item-number">${i}</span>${result}</li>`;
  }
  outputHTML += '</ul>';

  outputDiv.innerHTML = outputHTML;
});
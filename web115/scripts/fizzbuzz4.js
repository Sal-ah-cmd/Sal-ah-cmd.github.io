function checkDivision(num1, num2) {
  return num1 % num2 === 0;
}

document.getElementById('nameForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const firstName = document.getElementById('first_name').value.trim();
  const middleInitial = document.getElementById('middle_initial').value.trim();
  const lastName = document.getElementById('last_name').value.trim();
  const greetingElement = document.getElementById('greeting');
  const word1 = document.getElementById('word1').value.trim();
  const word2 = document.getElementById('word2').value.trim();
  const word3 = document.getElementById('word3').value.trim();
  const divisor1 = parseInt(document.getElementById('divisor1').value, 10);
  const divisor2 = parseInt(document.getElementById('divisor2').value, 10);
  const divisor3 = parseInt(document.getElementById('divisor3').value, 10);
  const totalCount = parseInt(document.getElementById('total_count').value, 10);
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

  if (isNaN(divisor1) || isNaN(divisor2) || isNaN(divisor3) || isNaN(totalCount) || totalCount <= 0 || divisor1 <= 0 || divisor2 <= 0 || divisor3 <= 0) {
    alert("Invalid input. Please enter positive numbers for all fields.");
    return;
  }

  const config = {
    limit: totalCount,
    divisors: {
      [divisor1]: { word: word1, class: 'fizz' },
      [divisor2]: { word: word2, class: 'buzz' },
      [divisor3]: { word: word3, class: 'bang' }
    }
  };

  let outputHTML = '<ul>';

  for (let i = 1; i <= config.limit; i++) {
    let result = '';
    let cssClasses = [];
    const divisorKeys = Object.keys(config.divisors);

    for (const divisor of divisorKeys) {
      if (checkDivision(i, Number(divisor))) {
        result += config.divisors[divisor].word + ' ';
        cssClasses.push(config.divisors[divisor].class);
      }
    }

    if (result.length > 0) {
      result = result.trim();
    } else {
      result = ''; 
    }

    const finalClass = 'fizzbuzz-item ' + cssClasses.join(' ');
    
    outputHTML += `<li class="${finalClass}">
                      <span class="item-number">${i}</span> ${result}
                  </li>`;
  }

  outputHTML += '</ul>';
  outputDiv.innerHTML = outputHTML;
});
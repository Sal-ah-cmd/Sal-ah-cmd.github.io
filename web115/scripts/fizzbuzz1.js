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

  const config = {
    limit: 140,
    divisors: {
      3: { word: 'Sneaky', class: 'fizz' },
      5: { word: 'Snake', class: 'buzz' }
    },
    defaultPhrase: 'Sss...'
  };

  let outputHTML = '<ul>';

  for (let i = 1; i <= config.limit; i++) {
    let result = '';
    let cssClass = '';

    if (i % 3 === 0 && i % 5 === 0) {
        result = `${config.divisors[3].word} ${config.divisors[5].word}`;
        cssClass = 'fizzbuzz';
    } else if (i % 3 === 0) {
        result = config.divisors[3].word;
        cssClass = config.divisors[3].class;
    } else if (i % 5 === 0) {
        result = config.divisors[5].word;
        cssClass = config.divisors[5].class;
    } else {
        result = config.defaultPhrase;
    }
    
    outputHTML += `<li class="fizzbuzz-item"><span class="item-number">${i})</span> <span class="${cssClass}">${result}</span></li>`;
  }

  outputHTML += '</ul>';
  outputDiv.innerHTML = outputHTML;
});
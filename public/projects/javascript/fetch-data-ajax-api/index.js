
// FUNZIONE DI RICERCA INGREDIENTE
async function SearchFood() {
  
  const mealsList = document.getElementById('meals-list');
  const template = document.getElementById('meal-template');

  if (!template) {
    console.error('Elemento #meal-template non trovato');
    return;
  }

  const ingredient = document.getElementById('ingredient-name').value.trim();
  if (!ingredient) return;

  try {
    const response = await fetch(`https://www.themealdb.com/api/json/v1/1/filter.php?i=${ingredient}`);
    const data = await response.json();

    mealsList.innerHTML = ''; // pulisci lista

    if (data.meals) {
      data.meals.forEach(meal => {
        const clone = template.content.cloneNode(true);
        const mealItem = clone.firstElementChild;

        mealItem.removeAttribute('id'); // rimuovi ID duplicato

        mealItem.querySelector('.meal-title').textContent = meal.strMeal;
        const img = mealItem.querySelector('.meal-image');
        img.src = meal.strMealThumb;
        img.alt = meal.strMeal;

        mealsList.appendChild(mealItem);
      });
    } else {
      mealsList.innerHTML = '<li class="text-red-500">Nessun piatto trovato</li>';
    }
  } catch (error) {
    console.error('Errore:', error);
    mealsList.innerHTML = '<li class="text-red-500">Errore nel recupero dati</li>';
  }
}

// FUNZIONE DI RICERCA BEVANDE
async function SearchDrink() {
  
  const drinkList = document.getElementById('drink-list');
  const template = document.getElementById('drink-template');

  if (!template) {
    console.error('Elemento #drink-template non trovato');
    return;
  }

  const drinkName = document.getElementById('drink-name').value.trim();
  if (!drinkName) return;

  try {
    const url = `https://www.thecocktaildb.com/api/json/v1/1/search.php?s=${drinkName}`;
    const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': 'ab6b2d567bmsh2bc25422fefd49bp1508c9jsnb42e88c24531',
            'x-rapidapi-host': 'the-cocktail-db.p.rapidapi.com'
        }
    };

    const response = await fetch(url); // options da aggiungere nei parametri se ci fosse bisogno della chiave
    const data = await response.json(); // Assuming the response is in JSON format

    drinkList.innerHTML = ''; // pulisci lista

    if (data.drinks) {
      data.drinks.forEach(drink => {
        const clone = template.content.cloneNode(true);
        const drinkItem = clone.firstElementChild;

        drinkItem.classList.remove('hidden');
        drinkItem.removeAttribute('id'); // rimuovi ID duplicato

        drinkItem.querySelector('.drink-title').textContent = drink.strDrink;
        const img = drinkItem.querySelector('.drink-image');
        img.src = drink.strDrinkThumb;
        img.alt = drink.strImageAttribution;

        for (let i = 1; i <= 15; i++) {
          const current = drink[`strIngredient${i}`];
          const next = drink[`strIngredient${i + 1}`];
          const element = drinkItem.querySelector(`.drink-ingredient${i}`);

          if (current) {
            element.textContent = next ? `${current}, ` : current;
          } else {
            element.classList.add('hidden');
          }
        }
        
        
        drinkItem.querySelector('.drink-instruction').textContent = drink.strInstructions;

        drinkList.appendChild(drinkItem);
      });
    } else {
      drinkList.innerHTML = '<li class="text-red-500">Nessun drink trovato</li>';
    }
  } catch (error) {
    console.error('Errore:', error);
    drinkList.innerHTML = '<li class="text-red-500">Errore nel recupero dati</li>';
  }
}

// AZIONI INPUT SUL BOTTONE 'OTTIENI'
const searchButton = document.getElementById('search-food-button');
const drinkButton = document.getElementById('search-drink-button');

searchButton.addEventListener('click', async () => {
  SearchFood()
});

drinkButton.addEventListener('click', async () => {
  SearchDrink()
});




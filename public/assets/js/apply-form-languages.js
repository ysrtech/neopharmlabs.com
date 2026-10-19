/**
 * Dynamic language fields management for careers application form
 */
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('languages-container');
  const addBtn = document.getElementById('add-language');
  
  if (!container || !addBtn) {
    return;
  }

  // Get data from template
  const languages = window.languagesData || [];
  const levels = window.languageLevelsData || [];
  const currentLang = window.currentLang || 'en';
  
  let languageCount = 0;

  const texts = {
    en: {
      language: 'Language',
      level: 'Level',
      select: 'Select...'
    },
    fr: {
      language: 'Langue',
      level: 'Niveau',
      select: 'Sélectionner...'
    }
  };

  // Manual translations for languages and levels
  const translations = {
    languages: {
      'Français': 'French',
      'Anglais': 'English',
      'Espagnol': 'Spanish',
      'Allemand': 'German',
      'Italien': 'Italian',
      'Portugais': 'Portuguese',
      'Mandarin': 'Mandarin',
      'Arabe': 'Arabic',
      'Russe': 'Russian',
      'Chinois': 'Chinese',
      'Japonais': 'Japanese',
      'Autre': 'Other'
    },
    levels: {
      'Débutant': 'Beginner',
      'Intermédiaire': 'Intermediate',
      'Avancé': 'Advanced',
      'Courant': 'Fluent',
      'Natif': 'Native',
      'Fonctionnel': 'Functional',
      'Professionnel': 'Professional'
    }
  };

  const currentTexts = texts[currentLang] || texts.en;
  
  // Translate description if in English
  function translateDescription(description, type) {
    if (currentLang === 'en' && translations[type][description]) {
      return translations[type][description];
    }
    return description;
  }

  function addLanguageField() {
    const index = languageCount++;
    
    const fieldHtml = `
      <div class="language-field" data-index="${index}">
        <div class="form-row">
          <div class="form-group">
            <select name="languages[${index}][language_id]" class="form-input">
              <option value="">${currentTexts.select} ${currentTexts.language.toLowerCase()}</option>
              ${languages.map(lang => 
                `<option value="${lang.id}">${translateDescription(lang.description, 'languages')}</option>`
              ).join('')}
            </select>
          </div>
          <div class="form-group">
            <select name="languages[${index}][level_id]" class="form-input">
              <option value="">${currentTexts.select} ${currentTexts.level.toLowerCase()}</option>
              ${levels.map(level => 
                `<option value="${level.id}">${translateDescription(level.description, 'levels')}</option>`
              ).join('')}
            </select>
          </div>
          <button type="button" class="btn-remove-language" data-index="${index}">×</button>
        </div>
      </div>
    `;
    
    container.insertAdjacentHTML('beforeend', fieldHtml);
    
    // Add remove handler
    const removeBtn = container.querySelector(`.btn-remove-language[data-index="${index}"]`);
    removeBtn.addEventListener('click', function() {
      const field = this.closest('.language-field');
      field.remove();
    });
  }

  addBtn.addEventListener('click', addLanguageField);
});

/**
 * Custom form validation messages for careers application form
 */
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('apply-form');
  
  if (!form) {
    return;
  }

  // Get current language from HTML lang attribute
  const lang = document.documentElement.lang || 'en';
  
  // Validation messages
  const messages = {
    en: {
      required: 'Please fill out this field.',
      email: 'Please enter a valid email address.',
      emailInvalid: 'Please include an "@" in the email address.',
      file: 'Please select a file.',
      checkbox: 'Please check this box if you want to proceed.',
      tel: 'Please enter a valid phone number.'
    },
    fr: {
      required: 'Veuillez remplir ce champ.',
      email: 'Veuillez saisir une adresse e-mail valide.',
      emailInvalid: 'Veuillez inclure un "@" dans l\'adresse e-mail.',
      file: 'Veuillez sélectionner un fichier.',
      checkbox: 'Veuillez cocher cette case pour continuer.',
      tel: 'Veuillez saisir un numéro de téléphone valide.'
    }
  };

  // Get messages for current language
  const msg = messages[lang] || messages.en;

  // Apply custom validation messages
  const inputs = form.querySelectorAll('input[required], textarea[required]');
  
  inputs.forEach(input => {
    input.addEventListener('invalid', function(e) {
      // Don't prevent default - we want the browser to show the validation bubble
      
      // Reset any previous custom message
      this.setCustomValidity('');
      
      // Set appropriate message based on validation state
      if (this.validity.valueMissing) {
        if (this.type === 'file') {
          this.setCustomValidity(msg.file);
        } else if (this.type === 'checkbox') {
          this.setCustomValidity(msg.checkbox);
        } else if (this.type === 'email') {
          this.setCustomValidity(msg.email);
        } else if (this.type === 'tel') {
          this.setCustomValidity(msg.tel);
        } else {
          this.setCustomValidity(msg.required);
        }
      } else if (this.validity.typeMismatch) {
        if (this.type === 'email') {
          this.setCustomValidity(msg.emailInvalid);
        } else {
          this.setCustomValidity(msg.required);
        }
      } else if (this.validity.patternMismatch) {
        if (this.type === 'tel') {
          this.setCustomValidity(msg.tel);
        } else if (this.type === 'email') {
          this.setCustomValidity(msg.emailInvalid);
        } else {
          this.setCustomValidity(msg.required);
        }
      }
    });

    // Reset custom validity on input
    input.addEventListener('input', function() {
      this.setCustomValidity('');
    });
    
    // Also reset on change for checkboxes/files
    input.addEventListener('change', function() {
      this.setCustomValidity('');
    });
  });

  // File upload display
  const fileInputs = form.querySelectorAll('input[type="file"]');
  
  fileInputs.forEach(fileInput => {
    const fileUpload = fileInput.closest('.file-upload');
    const fileLabel = fileInput.nextElementSibling;
    const fileText = fileLabel.querySelector('.file-text');
    
    // Create remove button
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'file-remove';
    removeBtn.innerHTML = '×';
    removeBtn.title = lang === 'fr' ? 'Supprimer le fichier' : 'Remove file';
    removeBtn.style.display = 'none';
    fileLabel.appendChild(removeBtn);
    
    // Handle file selection
    fileInput.addEventListener('change', function() {
      if (this.files && this.files.length > 0) {
        const fileName = this.files[0].name;
        fileText.textContent = fileName;
        fileLabel.classList.add('file-selected');
        removeBtn.style.display = 'flex';
      } else {
        resetFileInput();
      }
    });
    
    // Handle remove button click
    removeBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      fileInput.value = '';
      resetFileInput();
    });
    
    function resetFileInput() {
      const defaultText = lang === 'fr' ? 'Choisir un fichier ou glisser ici' : 'Choose file or drag here';
      fileText.textContent = defaultText;
      fileLabel.classList.remove('file-selected');
      removeBtn.style.display = 'none';
    }
  });
});

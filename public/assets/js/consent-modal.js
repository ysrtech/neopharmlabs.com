/**
 * Consent Modal Handler
 * Displays consent policy text in a modal when clicking consent links
 */

(function() {
    'use strict';
    
    const modal = document.getElementById('consent-modal');
    const modalTitle = document.getElementById('consent-modal-title');
    const modalBody = document.getElementById('consent-modal-body');
    const closeButtons = document.querySelectorAll('.consent-modal-close');
    const overlay = document.querySelector('.consent-modal-overlay');
    
    // Get consent data from global variable
    const consents = window.consentData || [];
    
    /**
     * Open modal with consent content
     */
    function openModal(consentId, lang) {
        const consent = consents.find(c => c.id == consentId);
        
        if (!consent) {
            console.error('Consent not found:', consentId);
            return;
        }
        
        // Set title
        if (consentId == 1) {
            modalTitle.textContent = lang === 'en' 
                ? 'Data Processing Policy' 
                : 'Politique de traitement des données';
        } else {
            modalTitle.textContent = lang === 'en' 
                ? 'Pre-Employment Verification Policy' 
                : 'Politique de vérification pré-emploi';
        }
        
        // Set content (HTML from API)
        const description = lang === 'en' ? consent.description_en : consent.description_fr;
        modalBody.innerHTML = description;
        
        // Show modal
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    /**
     * Close modal
     */
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    // Event listeners for consent links
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('consent-link') || e.target.closest('.consent-link')) {
            e.preventDefault();
            const link = e.target.classList.contains('consent-link') ? e.target : e.target.closest('.consent-link');
            const consentId = link.getAttribute('data-consent-id');
            const lang = link.getAttribute('data-consent-lang');
            openModal(consentId, lang);
        }
    });
    
    // Event listeners for close buttons
    closeButtons.forEach(function(button) {
        button.addEventListener('click', closeModal);
    });
    
    // Close on overlay click
    if (overlay) {
        overlay.addEventListener('click', closeModal);
    }
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
})();

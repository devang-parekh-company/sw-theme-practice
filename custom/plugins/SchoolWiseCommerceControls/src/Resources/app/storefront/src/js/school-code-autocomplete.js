import Plugin from 'src/plugin-system/plugin.class';

export default class SchoolCodeAutocomplete extends Plugin {
    init() {
        // this.inputElement = this.el; // The input element with school code
        // this.suggestionsContainer = this.getSuggestionsContainer();
        // this.form = this.inputElement.closest('form'); // Get the form element
        // this.submitButton = this.form.querySelector('[type="submit"]'); // Find the submit button
        // this.suggestionsList = [];
        //
        // this.registerEvents();
    }

    registerEvents() {
        this.inputElement.addEventListener('input', this.onInput.bind(this));
        this.form.addEventListener('submit', (event) => {
            if (!this.validateSelection()) {
                event.preventDefault(); // Stop form submission if invalid
                const errorMessage = this.el.getAttribute('data-error-message');
                alert(errorMessage);
            }
        });

        // Listen for blur event to validate after user stops typing
        this.inputElement.addEventListener('blur', this.validateSelection.bind(this));
    }

    getSuggestionsContainer() {
        // Find the sibling autocomplete suggestions container dynamically
        let container = this.el.nextElementSibling;
        if (container && container.classList.contains('autocomplete-suggestions')) {
            return container;
        } else {
            return null;
        }
    }

    onInput(event) {
        const query = event.target.value.trim();
        if (query.length > 2) {
            this.fetchSchoolCodes(query);
        } else {
            this.clearSuggestions();
            this.validateSelection(); // Revalidate when input is cleared
        }
    }

    async fetchSchoolCodes(query) {
        try {
            const baseUrl = this.el.getAttribute('data-base-url');
            const response = await fetch(`${baseUrl}/school-codes?term=${encodeURIComponent(query)}`);

            if (!response.ok) {
                throw new Error('Failed to fetch school codes');
            }

            const data = await response.json();
            this.suggestionsList = data.map(group => group.name); // Store valid suggestions
            this.renderSuggestions(data);
        } catch (error) {
            console.error('Error fetching school codes:', error);
        }
    }

    renderSuggestions(groups) {
        if (!this.suggestionsContainer) return;

        this.clearSuggestions();

        if (groups.length === 0) {
            // Display "No code found" message
            const noCodeMessage = document.createElement('div');
            noCodeMessage.className = 'no-code-message';
            noCodeMessage.textContent = 'Kein Code gefunden.';
            this.suggestionsContainer.appendChild(noCodeMessage);
        } else {
            groups.forEach(group => {
                const suggestionItem = document.createElement('div');
                suggestionItem.className = 'autocomplete-item';
                suggestionItem.textContent = group.name;

                suggestionItem.addEventListener('click', () => {
                    this.inputElement.value = group.name;
                    this.clearSuggestions();
                    this.validateSelection(); // Revalidate when a suggestion is selected
                });

                this.suggestionsContainer.appendChild(suggestionItem);
            });
        }

        this.suggestionsContainer.style.display = 'block';
    }

    clearSuggestions() {
        if (this.suggestionsContainer) {
            this.suggestionsContainer.innerHTML = '';
            this.suggestionsContainer.style.display = 'none';
        }
    }

    validateSelection() {
        const value = this.inputElement.value.trim();
        if (this.suggestionsList.includes(value)) {
            this.inputElement.setCustomValidity(''); // Valid input
            this.updateSubmitButtonState(true);
            return true;
        } else {
            this.inputElement.setCustomValidity('Please select a valid School Code.'); // Invalid input
            this.updateSubmitButtonState(false);
            return false;
        }
    }

    updateSubmitButtonState(isValid) {
        if (this.submitButton) {
            this.submitButton.disabled = !isValid; // Enable or disable the submit button
        }
    }
}
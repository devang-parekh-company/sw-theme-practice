import Plugin from 'src/plugin-system/plugin.class';

export default class ValidateSchoolKey extends Plugin {
    init() {
        this.inputField = document.querySelector('#school-key');
        this.submitBtn = document.querySelector('#submit-btn');
        this.validationIcon = document.querySelector('#validation-icon');

        this.submitBtn.disabled = true; // Initially disable the button

        if (this.inputField) {
            this.inputField.addEventListener("blur", () => this.validateSchoolKey());
            this.inputField.addEventListener("input", () => this.resetValidationIcon()); // Reset icon on input
        }

        if (this.submitBtn) {
            this.submitBtn.addEventListener("click", () => this.storeSchoolKey());
        }
    }

    async validateSchoolKey() {
        const schoolKey = this.inputField.value.trim();

        if (!schoolKey) {
            this.updateValidationIcon(false);
            this.submitBtn.disabled = true;
            return;
        }

        try {
            const baseUrl = window.appBaseUrl || document.querySelector('base')?.getAttribute('href') || '';
            const response = await fetch(`${baseUrl}/validate-school-key`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ schoolKey }),
            });

            const result = await response.json();

            if (!response.ok || !result.valid) {
                throw new Error(result.message || 'Invalid school key');
            }

            this.submitBtn.disabled = false; // Enable button
            this.updateValidationIcon(true); // Show ✅
        } catch (error) {
            this.submitBtn.disabled = true; // Keep button disabled
            this.updateValidationIcon(false); // Show ❌
        }
    }

    async storeSchoolKey() {
        const schoolKey = this.inputField.value.trim();
        const baseUrl = window.appBaseUrl || document.querySelector('base')?.getAttribute('href') || '';

        // Show loader effect and disable the button
        this.submitBtn.disabled = true;
        this.submitBtn.classList.add('loading'); // Add loading class

        try {
            const response = await fetch(`${baseUrl}/store-school-key`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ schoolKey }),
            });

            const data = await response.json();

            if (data.success && data.redirectUrl) {
                window.location.href = data.redirectUrl; // Redirect to the target page
            } else {
                console.error('Error:', data.error || 'Unexpected error occurred');
                this.submitBtn.classList.remove('loading'); // Remove loading class on error
                this.submitBtn.disabled = false;
            }
        } catch (error) {
            console.error('Fetch error:', error);
            this.submitBtn.classList.remove('loading'); // Remove loading class on fetch error
            this.submitBtn.disabled = false;
        }
    }

    updateValidationIcon(isValid) {
        if (this.inputField.value.trim() === "") {
            this.validationIcon.className = ""; // Remove all classes if input is empty
        } else {
            this.validationIcon.classList.remove("valid-icon", "invalid-icon"); // Remove previous states
            this.validationIcon.classList.add(isValid ? "valid-icon" : "invalid-icon");
        }
    }

    resetValidationIcon() {
        this.validationIcon.className = ""; // Clear icon when typing again
    }
}
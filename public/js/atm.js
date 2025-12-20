/**
 * ATM Withdrawal System
 * Handles withdrawal form, validation, and confirmation
 */

class ATMWithdrawal {
    constructor() {
        this.form = document.getElementById("withdraw-form");
        this.amountInput = document.getElementById("amount");
        this.accountOptions = document.querySelectorAll(
            'input[name="account_id"]'
        );
        this.quickAmountBtns = document.querySelectorAll(".quick-amount-btn");
        this.modal = document.getElementById("confirmation-modal");

        this.selectedAccount = null;
        this.selectedBalance = 0;
        this.transactionFee = 0; // Free for first 5 withdrawals

        this.init();
    }

    init() {
        // Account selection handlers
        this.accountOptions.forEach((option) => {
            option.addEventListener("change", () =>
                this.handleAccountSelection(option)
            );
        });

        // Quick amount buttons
        this.quickAmountBtns.forEach((btn) => {
            btn.addEventListener("click", () => this.selectQuickAmount(btn));
        });

        // Form submission
        if (this.form) {
            this.form.addEventListener("submit", (e) => this.handleSubmit(e));
        }

        // Modal handlers
        this.initModal();
    }

    handleAccountSelection(option) {
        const parent = option.closest(".account-option");
        this.selectedAccount = parent.dataset.account;
        this.selectedBalance = parseFloat(parent.dataset.balance);
    }

    selectQuickAmount(btn) {
        const amount = btn.dataset.amount;
        this.amountInput.value = amount;

        // Update active state
        this.quickAmountBtns.forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");
    }

    handleSubmit(e) {
        e.preventDefault();

        // Validate form
        if (!this.validateForm()) {
            return;
        }

        // Show confirmation modal
        this.showConfirmationModal();
    }

    validateForm() {
        const amount = parseFloat(this.amountInput.value);

        // Check if account is selected
        if (!this.selectedAccount) {
            this.showError("Please select an account");
            return false;
        }

        // Check if amount is entered
        if (!amount || amount <= 0) {
            this.showError("Please enter a valid amount");
            return false;
        }

        // Check minimum amount
        if (amount < 10) {
            this.showError("Minimum withdrawal amount is $10");
            return false;
        }

        // Check maximum amount
        if (amount > 5000) {
            this.showError(
                "Maximum withdrawal amount is $5,000 per transaction"
            );
            return false;
        }

        // Check if sufficient balance
        const total = amount + this.transactionFee;
        if (total > this.selectedBalance) {
            this.showError("Insufficient balance in selected account");
            return false;
        }

        return true;
    }

    showConfirmationModal() {
        const amount = parseFloat(this.amountInput.value);
        const fee = this.transactionFee;
        const total = amount + fee;

        const accountNames = {
            savings: "Savings Account",
            current: "Current Account",
            gold: "Gold Account",
        };

        // Update modal content
        document.getElementById("conf-account").textContent =
            accountNames[this.selectedAccount];
        document.getElementById(
            "conf-amount"
        ).textContent = `$${this.formatNumber(amount)}`;
        document.getElementById("conf-fee").textContent = `$${this.formatNumber(
            fee
        )}`;
        document.getElementById(
            "conf-total"
        ).textContent = `$${this.formatNumber(total)}`;

        // Show modal with animation
        if (this.modal) {
            this.modal.classList.add("active");
            this.modal.style.display = "flex";
            document.body.style.overflow = "hidden";
        }
    }

    initModal() {
        if (!this.modal) {
            console.error("Modal element not found");
            return;
        }

        // Close modal buttons
        const closeBtn = document.getElementById("close-modal");
        const cancelBtn = document.getElementById("cancel-modal");
        const overlay = this.modal.querySelector(".modal-overlay");

        if (closeBtn) {
            closeBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.closeModal();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.closeModal();
            });
        }

        if (overlay) {
            overlay.addEventListener("click", (e) => {
                if (e.target === overlay) {
                    this.closeModal();
                }
            });
        }

        // Fingerprint verification
        const fingerprintBtn = document.getElementById("verify-fingerprint");
        if (fingerprintBtn) {
            fingerprintBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.verifyFingerprint();
            });
        }

        // Confirm withdrawal
        const confirmBtn = document.getElementById("confirm-withdrawal");
        if (confirmBtn) {
            confirmBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.confirmWithdrawal();
            });
        }

        // ESC key to close
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.modal.classList.contains("active")) {
                this.closeModal();
            }
        });
    }

    closeModal() {
        this.modal.classList.remove("active");
        this.modal.style.display = "none";
        document.body.style.overflow = "";
    }

    verifyFingerprint() {
        const btn = document.getElementById("verify-fingerprint");

        // Disable button
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

        // Simulate fingerprint verification
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check"></i> Verified!';
            btn.style.background = "var(--success-color)";
            btn.style.color = "white";
            btn.style.borderColor = "var(--success-color)";

            // Auto-confirm after verification
            setTimeout(() => {
                this.confirmWithdrawal();
            }, 1000);
        }, 2000);
    }

    confirmWithdrawal() {
        const confirmBtn = document.getElementById("confirm-withdrawal");

        // Disable button and show loading
        confirmBtn.disabled = true;
        confirmBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Processing...';

        // Simulate processing
        setTimeout(() => {
            // Submit the actual form
            this.form.submit();
        }, 1500);
    }

    showError(message) {
        // Create error alert
        const existingAlert = document.querySelector(".alert-error");
        if (existingAlert) {
            existingAlert.remove();
        }

        const alert = document.createElement("div");
        alert.className = "alert alert-error";
        alert.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
            <button class="alert-close">&times;</button>
        `;

        // Insert at top of form
        this.form.insertBefore(alert, this.form.firstChild);

        // Add close handler
        alert.querySelector(".alert-close").addEventListener("click", () => {
            alert.remove();
        });

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert.parentElement) {
                alert.remove();
            }
        }, 5000);

        // Scroll to top
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    }

    formatNumber(num) {
        return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("withdraw-form")) {
        new ATMWithdrawal();
    }
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

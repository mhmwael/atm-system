/**
 * ATM Transfer System
 * Handles money transfer form, validation, and confirmation
 */

class ATMTransfer {
    constructor() {
        this.form = document.getElementById("transfer-form");
        this.amountInput = document.getElementById("amount");
        this.toAccountInput = document.getElementById("to_account");
        this.fromAccountOptions = document.querySelectorAll(
            'input[name="from_account_id"]'
        );
        this.quickAmountBtns = document.querySelectorAll(".quick-amount-btn");
        this.modal = document.getElementById("confirmation-modal");

        this.selectedFromAccount = null;
        this.selectedBalance = 0;
        this.transferFee = 0; // Free transfers

        this.init();
    }

    init() {
        // Account selection handlers
        this.fromAccountOptions.forEach((option) => {
            option.addEventListener("change", () =>
                this.handleAccountSelection(option)
            );
        });

        // To account input handler (for demo recipient lookup)
        if (this.toAccountInput) {
            this.toAccountInput.addEventListener("input", () =>
                this.lookupRecipient()
            );
        }

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
        this.selectedFromAccount = parent.dataset.account;
        this.selectedBalance = parseFloat(parent.dataset.balance);
    }

    lookupRecipient() {
        const accountNumber = this.toAccountInput.value.replace(/\s/g, "");
        const recipientInfo = document.getElementById("recipient-info");

        // Show recipient info when account number is 20 digits
        if (accountNumber.length === 20) {
            // Fetch recipient info from server
            fetch("/api/account-holder/" + accountNumber)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        document.getElementById("recipient-name").textContent =
                            data.name;
                        document.getElementById("recipient-bank").textContent =
                            "SecureBank • Account verified";
                        recipientInfo.style.display = "block";
                    } else {
                        document.getElementById("recipient-name").textContent =
                            "Account not found";
                        recipientInfo.style.display = "block";
                    }
                })
                .catch((error) => {
                    console.warn("[Transfer] Error looking up account:", error);
                    recipientInfo.style.display = "none";
                });
        } else {
            recipientInfo.style.display = "none";
        }
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
        const toAccount = this.toAccountInput.value.replace(/\s/g, "");

        // Check if from account is selected
        if (!this.selectedFromAccount) {
            this.showError("Please select an account to transfer from");
            return false;
        }

        // Check if to account is entered
        if (!toAccount || toAccount.length === 0) {
            this.showError("Please enter the recipient account number");
            return false;
        }

        // Check account number length
        if (toAccount.length < 10 || toAccount.length > 20) {
            this.showError(
                "Please enter a valid account number (10-20 digits)"
            );
            return false;
        }

        // Check if amount is entered
        if (!amount || amount <= 0) {
            this.showError("Please enter a valid amount");
            return false;
        }

        // Check minimum amount
        if (amount < 1) {
            this.showError("Minimum transfer amount is $1");
            return false;
        }

        // Check maximum amount
        if (amount > 10000) {
            this.showError(
                "Maximum transfer amount is $10,000 per transaction"
            );
            return false;
        }

        // Check if sufficient balance
        const total = amount + this.transferFee;
        if (total > this.selectedBalance) {
            this.showError("Insufficient balance in selected account");
            return false;
        }

        return true;
    }

    showConfirmationModal() {
        const amount = parseFloat(this.amountInput.value);
        const fee = this.transferFee;
        const total = amount + fee;
        const toAccount = this.toAccountInput.value;

        const accountNames = {
            savings: "Savings Account",
            current: "Current Account",
            gold: "Gold Account",
        };

        // Format account number for display
        const formattedToAccount = this.formatAccountNumber(toAccount);

        // Update modal content
        document.getElementById("conf-from-account").textContent =
            accountNames[this.selectedFromAccount];
        document.getElementById("conf-to-account").textContent =
            formattedToAccount;
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

        // Confirm transfer
        const confirmBtn = document.getElementById("confirm-transfer");
        if (confirmBtn) {
            confirmBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.confirmTransfer();
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
                this.confirmTransfer();
            }, 1000);
        }, 2000);
    }

    confirmTransfer() {
        const confirmBtn = document.getElementById("confirm-transfer");

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

    formatAccountNumber(account) {
        // Format account number as: **** **** **** 1234
        const clean = account.replace(/\s/g, "");
        const lastFour = clean.slice(-4);
        return `****  ****  ****  ${lastFour}`;
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("transfer-form")) {
        new ATMTransfer();
    }
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

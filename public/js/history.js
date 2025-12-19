/**
 * Transaction History System
 * Handles filtering, searching, and displaying transaction history
 */

class TransactionHistory {
    constructor() {
        this.periodFilter = document.getElementById("period-filter");
        this.typeFilter = document.getElementById("type-filter");
        this.accountFilter = document.getElementById("account-filter");
        this.resetBtn = document.getElementById("reset-filters");
        this.loadMoreBtn = document.getElementById("load-more-btn");
        this.downloadBtn = document.getElementById("download-btn");
        this.printBtn = document.getElementById("print-btn");
        this.modal = document.getElementById("details-modal");

        this.allTransactions = document.querySelectorAll(".transaction-item");

        this.init();
    }

    init() {
        // Filter event listeners
        if (this.periodFilter) {
            this.periodFilter.addEventListener("change", () =>
                this.applyFilters()
            );
        }

        if (this.typeFilter) {
            this.typeFilter.addEventListener("change", () =>
                this.applyFilters()
            );
        }

        if (this.accountFilter) {
            this.accountFilter.addEventListener("change", () =>
                this.applyFilters()
            );
        }

        // Reset filters
        if (this.resetBtn) {
            this.resetBtn.addEventListener("click", () => this.resetFilters());
        }

        // Load more button
        if (this.loadMoreBtn) {
            this.loadMoreBtn.addEventListener("click", () => this.loadMore());
        }

        // Download statement
        if (this.downloadBtn) {
            this.downloadBtn.addEventListener("click", () =>
                this.downloadStatement()
            );
        }

        // Print
        if (this.printBtn) {
            this.printBtn.addEventListener("click", () =>
                this.printStatement()
            );
        }

        // Modal handlers
        this.initModal();
    }

    applyFilters() {
        const typeValue = this.typeFilter.value;
        const accountValue = this.accountFilter.value;

        let visibleCount = 0;

        this.allTransactions.forEach((item) => {
            const itemType = item.dataset.type;
            const itemAccount = item.dataset.account;

            let showItem = true;

            // Type filter
            if (typeValue !== "all" && itemType !== typeValue) {
                showItem = false;
            }

            // Account filter
            if (accountValue !== "all" && itemAccount !== accountValue) {
                showItem = false;
            }

            // Show/hide item
            if (showItem) {
                item.style.display = "flex";
                visibleCount++;
            } else {
                item.style.display = "none";
            }
        });

        // Update count
        const countElement = document.querySelector(
            ".transaction-count strong"
        );
        if (countElement) {
            countElement.textContent = visibleCount;
        }

        // Show message if no results
        this.showNoResults(visibleCount === 0);
    }

    resetFilters() {
        // Reset all filters to default
        if (this.periodFilter) this.periodFilter.value = "30";
        if (this.typeFilter) this.typeFilter.value = "all";
        if (this.accountFilter) this.accountFilter.value = "all";

        // Show all transactions
        this.allTransactions.forEach((item) => {
            item.style.display = "flex";
        });

        // Update count
        const countElement = document.querySelector(
            ".transaction-count strong"
        );
        if (countElement) {
            countElement.textContent = this.allTransactions.length;
        }

        // Remove no results message
        this.showNoResults(false);
    }

    showNoResults(show) {
        const list = document.querySelector(".transactions-list");
        let noResults = list.querySelector(".no-results");

        if (show && !noResults) {
            noResults = document.createElement("div");
            noResults.className = "no-results";
            noResults.innerHTML = `
                <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                    <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <h3 style="margin-bottom: 0.5rem;">No transactions found</h3>
                    <p>Try adjusting your filters to see more results</p>
                </div>
            `;
            list.appendChild(noResults);
        } else if (!show && noResults) {
            noResults.remove();
        }
    }

    loadMore() {
        const btn = this.loadMoreBtn;

        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

        // Simulate loading more transactions
        setTimeout(() => {
            btn.innerHTML =
                '<i class="fas fa-check"></i> All transactions loaded';
            btn.style.background = "var(--success-color)";
            btn.style.color = "white";
            btn.style.borderColor = "var(--success-color)";

            setTimeout(() => {
                btn.style.display = "none";
            }, 2000);
        }, 1500);
    }

    downloadStatement() {
        const btn = this.downloadBtn;

        // Show loading
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        // Simulate download
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check"></i>';

            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-download"></i>';
                btn.disabled = false;
            }, 1500);

            // Show success message
            this.showMessage("Statement downloaded successfully!", "success");
        }, 1000);
    }

    printStatement() {
        // In a real app, this would open print dialog
        window.print();
    }

    initModal() {
        if (!this.modal) return;

        // Close buttons
        const closeBtn = document.getElementById("close-modal");
        const closeDetailsBtn = document.getElementById("close-details");
        const overlay = this.modal.querySelector(".modal-overlay");

        if (closeBtn) {
            closeBtn.addEventListener("click", () => this.closeModal());
        }

        if (closeDetailsBtn) {
            closeDetailsBtn.addEventListener("click", () => this.closeModal());
        }

        if (overlay) {
            overlay.addEventListener("click", (e) => {
                if (e.target === overlay) {
                    this.closeModal();
                }
            });
        }

        // Download receipt
        const downloadReceipt = document.getElementById("download-receipt");
        if (downloadReceipt) {
            downloadReceipt.addEventListener("click", () => {
                this.showMessage("Receipt downloaded successfully!", "success");
                this.closeModal();
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

    showMessage(message, type = "success") {
        const alert = document.createElement("div");
        alert.className = `alert alert-${type}`;
        alert.style.position = "fixed";
        alert.style.top = "100px";
        alert.style.right = "20px";
        alert.style.zIndex = "99999";
        alert.innerHTML = `
            <i class="fas fa-${
                type === "success" ? "check" : "exclamation"
            }-circle"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
}

// Global function for showing transaction details
function showTransactionDetails(button) {
    const item = button.closest(".transaction-item");
    const title = item.querySelector(".transaction-title").textContent;
    const meta = item.querySelector(".transaction-meta span").textContent;
    const amount = item.querySelector(".transaction-amount").textContent;
    const type = item.dataset.type;
    const account = item.dataset.account;

    const accountNames = {
        savings: "Savings Account",
        current: "Current Account",
        gold: "Gold Account",
    };

    // Update modal content
    document.getElementById("detail-id").textContent =
        "TXN-" + Date.now().toString().slice(-10);
    document.getElementById("detail-type").textContent =
        type.charAt(0).toUpperCase() + type.slice(1);
    document.getElementById("detail-date").textContent = meta;
    document.getElementById("detail-account").textContent =
        accountNames[account] || account;
    document.getElementById("detail-amount").textContent = amount;
    document.getElementById("detail-ref").textContent =
        "REF-" + Math.random().toString(36).substring(2, 10).toUpperCase();

    // Show modal
    const modal = document.getElementById("details-modal");
    modal.classList.add("active");
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".transactions-list")) {
        new TransactionHistory();
    }
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

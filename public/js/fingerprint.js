/**
 * Fingerprint Authentication System
 * Uses WebAuthn API for biometric authentication
 */

class FingerprintAuth {
    constructor() {
        this.scanButton = document.getElementById("scan-fingerprint");
        this.statusText = document.querySelector(".scanner-status");
        this.scannerAnimation = document.querySelector(".scanner-animation");
        this.fingerprintForm = document.getElementById("fingerprint-form");

        this.init();
    }

    init() {
        if (this.scanButton) {
            this.scanButton.addEventListener("click", () => this.startScan());
        }

        // Check if WebAuthn is available
        this.checkBrowserSupport();
    }

    checkBrowserSupport() {
        if (!window.PublicKeyCredential) {
            this.showError(
                "Biometric authentication is not supported on this browser"
            );
            if (this.scanButton) {
                this.scanButton.disabled = true;
            }
            return false;
        }
        return true;
    }

    async startScan() {
        const userId = document.getElementById("user_id").value.trim();

        if (!userId) {
            this.showError("Please enter your User ID first");
            return;
        }

        if (!this.checkBrowserSupport()) {
            return;
        }

        try {
            this.updateStatus("Scanning fingerprint...", "scanning");

            // For demo purposes, we'll simulate fingerprint scanning
            // In production, this would use WebAuthn API
            await this.simulateFingerprintScan();

            // If you want to implement real WebAuthn:
            // const credential = await this.authenticateWithWebAuthn(userId);

            this.updateStatus("Fingerprint verified successfully!", "success");

            // Submit the form after successful scan
            setTimeout(() => {
                this.fingerprintForm.submit();
            }, 1000);
        } catch (error) {
            this.showError(
                error.message || "Fingerprint scan failed. Please try again."
            );
        }
    }

    /**
     * Simulate fingerprint scanning (for demo)
     * Remove this in production and use real WebAuthn
     */
    simulateFingerprintScan() {
        return new Promise((resolve, reject) => {
            // Simulate scanning delay
            setTimeout(() => {
                // Simulate random success/failure (90% success rate for demo)
                const success = Math.random() > 0.1;

                if (success) {
                    // Generate a demo fingerprint token
                    const token = this.generateDemoToken();
                    document.getElementById("fingerprint_data").value = token;
                    resolve(token);
                } else {
                    reject(new Error("Fingerprint not recognized"));
                }
            }, 2000);
        });
    }

    /**
     * Generate a demo authentication token
     */
    generateDemoToken() {
        const timestamp = Date.now();
        const random = Math.random().toString(36).substring(2);
        return btoa(`fingerprint_${timestamp}_${random}`);
    }

    /**
     * Real WebAuthn Implementation (for production use)
     */
    async authenticateWithWebAuthn(userId) {
        try {
            // This would require server-side setup
            // 1. Get challenge from server
            const challengeResponse = await fetch("/api/auth/challenge", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({ user_id: userId }),
            });

            const challengeData = await challengeResponse.json();

            // 2. Create credential request
            const publicKeyCredentialRequestOptions = {
                challenge: Uint8Array.from(challengeData.challenge, (c) =>
                    c.charCodeAt(0)
                ),
                allowCredentials: challengeData.allowCredentials.map(
                    (cred) => ({
                        id: Uint8Array.from(atob(cred.id), (c) =>
                            c.charCodeAt(0)
                        ),
                        type: "public-key",
                        transports: ["usb", "nfc", "ble", "internal"],
                    })
                ),
                timeout: 60000,
                userVerification: "required",
            };

            // 3. Request credential from authenticator
            const credential = await navigator.credentials.get({
                publicKey: publicKeyCredentialRequestOptions,
            });

            // 4. Send credential to server for verification
            const verifyResponse = await fetch("/api/auth/verify", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({
                    user_id: userId,
                    credential: {
                        id: credential.id,
                        rawId: btoa(
                            String.fromCharCode(
                                ...new Uint8Array(credential.rawId)
                            )
                        ),
                        response: {
                            authenticatorData: btoa(
                                String.fromCharCode(
                                    ...new Uint8Array(
                                        credential.response.authenticatorData
                                    )
                                )
                            ),
                            clientDataJSON: btoa(
                                String.fromCharCode(
                                    ...new Uint8Array(
                                        credential.response.clientDataJSON
                                    )
                                )
                            ),
                            signature: btoa(
                                String.fromCharCode(
                                    ...new Uint8Array(
                                        credential.response.signature
                                    )
                                )
                            ),
                            userHandle: credential.response.userHandle
                                ? btoa(
                                      String.fromCharCode(
                                          ...new Uint8Array(
                                              credential.response.userHandle
                                          )
                                      )
                                  )
                                : null,
                        },
                    },
                }),
            });

            return await verifyResponse.json();
        } catch (error) {
            console.error("WebAuthn error:", error);
            throw new Error("Biometric authentication failed");
        }
    }

    updateStatus(message, status = "idle") {
        if (this.statusText) {
            this.statusText.textContent = message;
            this.statusText.className = `scanner-status status-${status}`;
        }

        if (this.scannerAnimation) {
            this.scannerAnimation.className = `scanner-animation ${status}`;
        }
    }

    showError(message) {
        this.updateStatus(message, "error");

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
        `;

        const form = document.querySelector(".login-form");
        form.insertBefore(alert, form.firstChild);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            alert.remove();
            this.updateStatus("Waiting for fingerprint...", "idle");
        }, 5000);
    }
}

// Initialize fingerprint authentication when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new FingerprintAuth();
});

// Add additional status styles dynamically
const style = document.createElement("style");
style.textContent = `
    .scanner-status.status-scanning {
        color: #3b82f6;
        font-weight: 600;
    }

    .scanner-status.status-success {
        color: #10b981;
        font-weight: 600;
    }

    .scanner-status.status-error {
        color: #ef4444;
        font-weight: 600;
    }

    .scanner-animation.scanning {
        animation: scanPulse 1s infinite;
    }

    .scanner-animation.success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .scanner-animation.error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        animation: shake 0.5s;
    }

    @keyframes scanPulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
`;
document.head.appendChild(style);

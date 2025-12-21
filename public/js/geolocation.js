/**
 * Geolocation Handler
 * Gets user's current location via browser Geolocation API
 */

class GeolocationHandler {
    constructor() {
        this.latitude = null;
        this.longitude = null;
        this.isSupported = navigator.geolocation !== undefined;
    }

    /**
     * Get current location
     * Returns promise that resolves with {latitude, longitude}
     */
    getCurrentLocation() {
        return new Promise((resolve, reject) => {
            if (!this.isSupported) {
                console.warn("[Geolocation] Geolocation not supported");
                reject(new Error("Geolocation not supported"));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.latitude = position.coords.latitude;
                    this.longitude = position.coords.longitude;

                    console.log("[Geolocation] Location obtained:", {
                        latitude: this.latitude,
                        longitude: this.longitude,
                    });

                    resolve({
                        latitude: this.latitude,
                        longitude: this.longitude,
                    });
                },
                (error) => {
                    console.error(
                        "[Geolocation] Error getting location:",
                        error.message
                    );
                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            );
        });
    }

    /**
     * Get cached location (if available)
     */
    getCachedLocation() {
        return {
            latitude: this.latitude,
            longitude: this.longitude,
        };
    }
}

// Create global instance
const geoHandler = new GeolocationHandler();

// Also initialize on window load as fallback
window.addEventListener("load", function () {
    console.log("[Geolocation] Handler initialized:", {
        isSupported: geoHandler.isSupported,
        latitude: geoHandler.latitude,
        longitude: geoHandler.longitude,
    });
});

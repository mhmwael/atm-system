# Geolocation Setup Guide

## Important: HTTPS Required for Geolocation

Browser geolocation API only works on:

-   `https://` (secure)
-   `localhost` (development exception)
-   `127.0.0.1` (development exception)

## Testing on Phone (Required)

You MUST use a tunneling service to access your localhost from phone over HTTPS.

### Option 1: Ngrok (Recommended)

1. Download from https://ngrok.com/download
2. Extract and run:

```bash
ngrok http 8000
```

(Replace 8000 with your port)

3. Copy the HTTPS URL (e.g., `https://1234-56-78-90.ngrok.io`)
4. Open on phone browser using that URL
5. Allow location permission when prompted

### Option 2: LocalTunnel

1. Install Node.js if not already installed
2. Run:

```bash
npx localtunnel --port 8000
```

3. Copy the HTTPS URL provided
4. Open on phone using that URL

## Testing Checklist

✓ Open login page on phone via HTTPS URL
✓ Wait for location permission prompt (should appear immediately)
✓ Click "Allow" to share location
✓ Login with card & PIN
✓ Check database: `SELECT id, name, latitude, longitude FROM users;`
✓ Latitude/longitude should be filled (not NULL)

## Debugging

### Check browser console (F12):

Look for these messages:

```
[Login] Attempting to capture location...
[Login] ✓ Location captured: {latitude: 30.0444, longitude: 31.2357}
[Login] Form submitted with location: {lat: "30.0444", lon: "31.2357"}
```

### Check Laravel logs:

```bash
tail -f storage/logs/laravel.log
```

Look for:

```
[Login] Location data: {"latitude":"30.0444","longitude":"31.2357"}
[Login] Updating user location: {"user_id":2,"lat":"30.0444","lon":"31.2357"}
```

### If location is NULL:

1. Check your browser's location privacy settings
2. Ensure you're on HTTPS (not HTTP)
3. Check device location is enabled
4. Try a different browser

## For Transaction Location

Transactions will capture location when:

-   Withdraw page loaded
-   Transfer page loaded
-   Form submitted

Same HTTPS requirement applies.

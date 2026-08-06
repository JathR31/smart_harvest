/**
 * Small sessionStorage-backed fetch cache: survives full-page navigation
 * within a browser tab/session (unlike a plain JS variable, which resets on
 * every page load in this multi-page app), and clears when the tab closes.
 *
 * On a fresh URL, fetches and caches. On a cached-but-stale URL, returns the
 * cached data immediately (fresh = false) while a background fetch quietly
 * revalidates and invokes onStale with the new data when it lands.
 */
async function cachedFetch(url, { ttlMs = 60000, onStale } = {}) {
    const key = 'sh_cache_' + url;
    const cached = sessionStorage.getItem(key);

    if (cached) {
        let entry;
        try {
            entry = JSON.parse(cached);
        } catch (e) {
            entry = null;
        }

        if (entry) {
            const isFresh = (Date.now() - entry.ts) < ttlMs;

            if (!isFresh) {
                fetch(url)
                    .then(response => response.ok ? response.json() : null)
                    .then(fresh => {
                        if (fresh) {
                            sessionStorage.setItem(key, JSON.stringify({ data: fresh, ts: Date.now() }));
                            if (onStale) onStale(fresh);
                        }
                    })
                    .catch(() => {});
            }

            return { data: entry.data, fresh: isFresh };
        }
    }

    const response = await fetch(url);
    const data = await response.json();
    sessionStorage.setItem(key, JSON.stringify({ data, ts: Date.now() }));
    return { data, fresh: true };
}

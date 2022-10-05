(function (global) {
    'use strict';

    const useLogging = false;
    const cacheName = 'sw-cache';
    const startPage = 'https://xxxxxxxxx.xxx/?source=pwa';
    const offlinePage = 'https://xxxxxxxxx.xxx/offline';
    const filesToCache = [startPage, offlinePage];
    const neverCacheUrls = [/\/kms/];

    // Install
    global.addEventListener('install', function (e) {

        if(useLogging) console.log('Installing service worker..');

        self.skipWaiting();
        e.waitUntil(
            caches.open(cacheName).then(function (cache) {
                if(useLogging) console.log('Caching dependencies..');
                filesToCache.map(function (url) {
                    return cache.add(url).catch(function (reason) {
                        if(useLogging) console.log('Caching of url failed. Reason: ' + String(reason) + ' ' + url);
                    });
                });
            })
        );
    });

    // Activate
    global.addEventListener('activate', function (e) {
        if(useLogging) console.log('Service worker is now active..');
        e.waitUntil(
            caches.keys().then(function (keyList) {
                return Promise.all(keyList.map(function (key) {
                    if (key !== cacheName) {
                        if(useLogging) console.log('Removing old cache', key);
                        return caches.delete(key);
                    }
                }));
            })
        );
        return global.clients.claim();
    });

    // Fetch
    global.addEventListener('fetch', function (e) {

        // Return if the current request url is in the never cache list
        if (!neverCacheUrls.every(checkNeverCacheList, e.request.url)) {
            if(useLogging) console.log('Current request is excluded from cache: ' + e.request.url);
            return;
        }

        // Return if request url protocal isn't http or https
        if (!e.request.url.match(/^(http|https):\/\//i)) return;

        // Return if request url is from an external domain.
        if (new URL(e.request.url).origin !== location.origin) return;

        // For POST requests, do not use the cache. Serve offline page if offline.
        if (e.request.method !== 'GET') {
            e.respondWith(
                fetch(e.request).catch(function () {
                    return caches.match(offlinePage);
                })
            );
            return;
        }

        //If online, respond with network request
        if (navigator.onLine) {
            e.respondWith(
                fetch(e.request).then(function (response) {
                    return caches.open(cacheName).then(function (cache) {
                        cache.put(e.request, response.clone());
                        return response;
                    });
                })
            );
            return;
        }

        //not online, get from cache, or serve offline page
        e.respondWith(
            caches.match(e.request).then(function (response) {
                return response || fetch(e.request).then(function (response) {
                    return caches.open(cacheName).then(function (cache) {
                        cache.put(e.request, response.clone());
                        return response;
                    });
                });
            }).catch(function () {
                return caches.match(offlinePage);
            })
        );
    });

    // Check if current url is in the neverCacheUrls list
    function checkNeverCacheList(url) {
        if (this.match(url)) return false;
        return true;
    }

})(self);
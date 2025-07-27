import Shepherd from 'shepherd.js';

async function loadTourConfig(route) {
    try {
        const response = await fetch(`/tour-guide/get-steps-for-route?route=${route}`);
        if (response.ok) {
            const data = await response.json();
            if (data.steps && data.steps.length > 0) {
                return {
                    route: data.route,
                    version: data.version,
                    steps: data.steps,
                    defaultStepOptions: {}
                };
            }
        }
    } catch (e) {
        console.error(`Error loading tour config for route "${route}":`, e);
        return null;
    }
}

async function trackTourEvent(idTourGuide, eventType, tourVersion) {
    try {
        const response = await fetch(`/tour-guide/event/${eventType}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                idTourGuide: idTourGuide,
                tourVersion: tourVersion
            })
        });

        if (!response.ok) {
            console.error(`Error tracking tour ${eventType} event:`, response.statusText);
        }

        return response.ok;
    } catch (e) {
        console.error(`Error tracking tour ${eventType} event:`, e);
        return false;
    }
}

function createPauseButton(tour, route, version) {
    if (!document.getElementById('tour-pause-button-styles')) {
        const styleEl = document.createElement('style');
        styleEl.id = 'tour-pause-button-styles';
        document.head.appendChild(styleEl);
    }

    const addPauseButtonToFooter = () => {
        setTimeout(() => {
            const footers = document.querySelectorAll('.shepherd-footer');

            if (footers.length > 0) {
                const currentFooter = footers[footers.length - 1];

                if (!currentFooter.querySelector('.tour-pause-button')) {
                    const pauseButton = document.createElement('button');
                    pauseButton.className = 'tour-pause-button';
                    pauseButton.textContent = 'Pause';
                    pauseButton.title = 'Pause Tour';

                    pauseButton.addEventListener('click', () => {
                        const currentStepIndex = tour.getCurrentStep().id;
                        localStorage.setItem(`tourPaused_${route}_v${version}`, currentStepIndex);
                        if (window.tourConfig && window.tourConfig.idTourGuide) {
                            trackTourEvent(window.tourConfig.idTourGuide, 'pause', version);
                        }
                        tour.hide();
                    });

                    currentFooter.insertBefore(pauseButton, currentFooter.firstChild);
                }
            }
        }, 50);
    };

    tour.on('show', addPauseButtonToFooter);

    return addPauseButtonToFooter;
}

/**
 * Wait for an element to be present in the DOM
 * @param {string} selector - CSS selector for the element
 * @param {number} timeout - Maximum time to wait in milliseconds
 * @returns {Promise<Element|null>} - The found element or null if not found
 */
function waitForElement(selector, timeout = 3000) {
    return new Promise((resolve) => {
        const element = document.querySelector(selector);
        if (element) {
            resolve(element);
            return;
        }

        const timeoutId = setTimeout(() => {
            console.warn(`Timeout waiting for element: ${selector}`);
            resolve(null);
        }, timeout);

        const observer = new MutationObserver((mutations, obs) => {
            const element = document.querySelector(selector);
            if (element) {
                clearTimeout(timeoutId);
                obs.disconnect();
                resolve(element);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

/**
 * Creates a button in the footer to start the tour
 * @param {string} route - The route for the tour
 * @param {number} version - The version of the tour
 */
function createTourStartButton(route, version = 1) {
    if (document.getElementById('tour-start-button')) {
        return;
    }

    waitForElement('.footer').then(footer => {
        if (footer) {
            const startButton = document.createElement('i');
            startButton.id = 'tour-start-button';
            startButton.classList.add('fa', 'fa-map-signs');
            startButton.title = 'Start Guided Tour';

            startButton.addEventListener('click', () => {
                if (window.shepherdTour) {
                    window.shepherdTour.start();
                } else {
                    restartTour(route, version);
                }
            });

            footer.appendChild(startButton);
        }
    });
}

export async function initTourGuide(input = 'default') {
    let config;

    if (typeof input === 'string') {
        config = await loadTourConfig(input);
    } else {
        config = input;
    }

    if (!config) {
        console.warn('No tour config available.');
        return;
    }

    const {
        steps = [],
        defaultStepOptions = {},
        route = '/',
        version = 1
    } = config;

    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        useHistory: true,
        defaultStepOptions: {
            cancelIcon: {enabled: true},
            classes: 'shepherd-theme-default',
            scrollTo: true,
            ...defaultStepOptions,
            beforeShowPromise: function(step) {
                return new Promise((resolve) => {
                    if (step && step.options && step.options.attachTo && step.options.attachTo.element) {
                        const selector = step.options.attachTo.element;
                        waitForElement(selector).then(() => {
                            resolve();
                        });
                    } else {
                        resolve();
                    }
                });
            }
        }
    });

    steps.forEach((step, index) => {
        const isFirstStep = index === 0;
        const isLastStep = index === steps.length - 1;
        const buttons = [];

        if (!isFirstStep) {
            buttons.push({
                text: 'Back',
                classes: 'shepherd-button-secondary',
                action: tour.back
            });
        }

        if (!isLastStep) {
            buttons.push({
                text: 'Next',
                classes: 'shepherd-button-primary',
                action: tour.next
            });
        } else {
            buttons.push({
                text: 'Finish',
                classes: 'shepherd-button-primary',
                action: tour.complete
            });
        }

        tour.addStep({
            ...step,
            buttons: buttons
        });
    });

    const storageKey = `tourCompleted_${route}_v${version}`;

    tour.on('complete', () => {
        localStorage.setItem(storageKey, 'true');
        localStorage.removeItem(`tourPaused_${route}_v${version}`);
        if (window.tourConfig && window.tourConfig.idTourGuide) {
            trackTourEvent(window.tourConfig.idTourGuide, 'finish', version);
        }
    });

    tour.on('cancel', () => {
        localStorage.setItem(storageKey, 'true');
        localStorage.removeItem(`tourPaused_${route}_v${version}`);
        if (window.tourConfig && window.tourConfig.idTourGuide) {
            trackTourEvent(window.tourConfig.idTourGuide, 'finish', version);
        }
    });

    tour.on('start', () => {
        createPauseButton(tour, route, version);

        if (window.tourConfig && window.tourConfig.idTourGuide) {
            trackTourEvent(window.tourConfig.idTourGuide, 'start', version);
        }
    });

    window.shepherdTour = tour;

    const pausedStepId = localStorage.getItem(`tourPaused_${route}_v${version}`);
    if (pausedStepId) {
        tour.pausedStepId = pausedStepId;
    }

    // Add the tour start button to the footer when a tour is available
    createTourStartButton(route, version);

    return !localStorage.getItem(storageKey);
}


function restartTour(route, version = 1) {
    localStorage.removeItem(`tourCompleted_${route}_v${version}`);
    if (window.initTourGuide) {
        window.initTourGuide(route).then(() => {
            if (window.shepherdTour) {
                window.shepherdTour.start();
            }
        });
    }
}

window.restartTour = restartTour;

/**
 * Wait for AJAX requests to complete and DOM to be fully loaded
 * @param {number} timeout - Maximum time to wait in milliseconds
 * @returns {Promise<void>}
 */
function waitForPageLoad(timeout = 5000) {
    return new Promise((resolve) => {
        if (document.readyState === 'complete') {
            setTimeout(resolve, 1500);
            return;
        }

        const timeoutId = setTimeout(() => {
            console.warn('Timeout waiting for page to fully load');
            resolve();
        }, timeout);

        window.addEventListener('load', () => {
            setTimeout(() => {
                clearTimeout(timeoutId);
                resolve();
            }, 1500);
        }, { once: true });
    });
}

/**
 * Automatically initialize and start the tour guide if not completed
 * @param {Object} config - Tour configuration object
 * @returns {Promise<void>}
 */
export async function autoStartTourGuide(config) {
    if (!config || !config.route) {
        console.warn('Invalid tour configuration');
        return;
    }

    const version = config.version || 1;
    const route = config.route;
    const storageKey = `tourCompleted_${route}_v${version}`;
    const pausedStepKey = `tourPaused_${route}_v${version}`;

    const isPaused = localStorage.getItem(pausedStepKey);
    const isNotCompleted = !localStorage.getItem(storageKey);

    // Always initialize the tour to make the start button available
    await waitForPageLoad();
    await initTourGuide(config);

    // Only auto-start the tour if it's paused or not completed
    if (isPaused || isNotCompleted) {
        if (window.shepherdTour) {
            window.shepherdTour.start();

            if (isPaused && window.shepherdTour.pausedStepId) {
                try {
                    const stepId = parseInt(window.shepherdTour.pausedStepId, 10);
                    window.shepherdTour.show(stepId);
                    localStorage.removeItem(pausedStepKey);
                } catch (e) {
                    console.error('Error resuming tour from paused step:', e);
                }
            }
        }
    }
}
